<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Services\IyzicoService;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Phase 11C — Iyzico 3DS payment flow.
 *
 * Routes (see web.php):
 *   POST /odeme/kart/{order}   → initiate()   — start 3DS, render Iyzico HTML form
 *   POST /odeme/3d-sonuc       → callback()   — Iyzico posts back after bank 3DS
 */
class PaymentController extends Controller
{
    public function __construct(
        private IyzicoService $iyzico,
        private OrderService  $orderService,
    ) {}

    /**
     * Start a 3DS credit-card payment for the given order.
     * Called from checkout when the user selects "Kredi Kartı".
     *
     * The order must already exist and belong to the current session/user.
     * Card data is validated here, passed directly to Iyzico, and NEVER stored.
     */
    public function initiate(Request $request, Order $order): mixed
    {
        // Security: order must belong to session or authenticated user
        $this->authorizeOrder($order);

        // Only pending orders can be paid
        if ($order->payment_status !== 'beklemede') {
            return redirect()->route('checkout.success')
                ->with('checkout_error', 'Bu sipariş zaten işlenmiş.');
        }

        $validated = $request->validate([
            'card_holder'       => ['required', 'string', 'max:100'],
            'card_number'       => ['required', 'string', 'regex:/^[\d\s]{13,19}$/'],
            'card_expire_month' => ['required', 'string', 'size:2', 'regex:/^(0[1-9]|1[0-2])$/'],
            'card_expire_year'  => ['required', 'string', 'size:4', 'regex:/^20\d{2}$/'],
            'card_cvc'          => ['required', 'string', 'regex:/^\d{3,4}$/'],
        ]);

        $cardData = [
            'holder'       => $validated['card_holder'],
            'number'       => $validated['card_number'],
            'expire_month' => $validated['card_expire_month'],
            'expire_year'  => $validated['card_expire_year'],
            'cvc'          => $validated['card_cvc'],
        ];

        try {
            $order->load('items');
            $html = $this->iyzico->initialize3DPayment($order, $cardData);
        } catch (\RuntimeException $e) {
            return redirect()->route('checkout.index')
                ->with('checkout_error', $e->getMessage());
        }

        // Render the Iyzico HTML directly (it auto-submits to the bank)
        return response($html)->header('Content-Type', 'text/html; charset=UTF-8');
    }

    /**
     * Iyzico 3DS callback — POSTed by Iyzico after bank authentication.
     * CSRF is disabled for this route (Iyzico is the caller, not our browser).
     *
     * On success: confirm charge, update order → redirect to success page.
     * On failure: update order payment_status to basarisiz → redirect with error.
     */
    public function callback(Request $request): mixed
    {
        $paymentId        = $request->input('paymentId', '');
        $conversationData = $request->input('conversationData', '');
        $conversationId   = $request->input('conversationId', '');
        $mdStatus         = (int) $request->input('mdStatus', 0);

        // mdStatus 1 = full 3DS auth; 2/3/4 = half 3DS (still acceptable for TR)
        // 0 / negative = auth failed
        if (! in_array($mdStatus, [1, 2, 3, 4], true)) {
            Log::warning('Iyzico 3DS mdStatus failed', compact('mdStatus', 'conversationId'));

            return $this->handlePaymentFailure(
                (int) $conversationId,
                'Kart doğrulama başarısız (mdStatus: ' . $mdStatus . ').'
            );
        }

        try {
            $result = $this->iyzico->confirm3DPayment($paymentId, $conversationData, $conversationId);
        } catch (\Throwable $e) {
            Log::error('Iyzico confirm3DPayment exception', [
                'conversation_id' => $conversationId,
                'error'           => $e->getMessage(),
            ]);

            return $this->handlePaymentFailure((int) $conversationId, 'Ödeme onaylanamadı.');
        }

        $order = Order::find((int) $conversationId);

        if (! $order) {
            Log::error('Iyzico callback: order not found', ['conversation_id' => $conversationId]);
            return redirect()->route('home');
        }

        DB::transaction(function () use ($order, $result, $paymentId): void {
            $this->orderService->recordOnlinePaymentResult(
                $order,
                $result['success'],
                $result['providerRef']
            );

            // Update the pending Payment row
            Payment::where('order_id', $order->id)
                ->where('provider', 'iyzico')
                ->where('status', 'pending')
                ->latest()
                ->first()
                ?->update([
                    'status'       => $result['success'] ? 'paid' : 'failed',
                    'provider_ref' => $result['providerRef'],
                    'paid_at'      => $result['success'] ? now() : null,
                ]);
        });

        if ($result['success']) {
            session(['checkout_order_id' => $order->id]);
            session(['cart_count' => 0]);

            return redirect()->route('checkout.success');
        }

        return redirect()->route('checkout.index')
            ->with('checkout_error', 'Ödeme başarısız: ' . $result['message']);
    }

    // ── Private ───────────────────────────────────────────────────────────────

    private function authorizeOrder(Order $order): void
    {
        if (auth()->check()) {
            abort_unless($order->user_id === auth()->id(), 403);
        } else {
            // Guest: order ID must match what we stored in session after checkout
            abort_unless(session('checkout_order_id') === $order->id || session('pending_order_id') === $order->id, 403);
        }
    }

    private function handlePaymentFailure(int $orderId, string $message): mixed
    {
        if ($orderId) {
            $order = Order::find($orderId);
            if ($order) {
                $this->orderService->recordOnlinePaymentResult($order, false, '');
            }
        }

        return redirect()->route('checkout.index')
            ->with('checkout_error', $message);
    }
}
