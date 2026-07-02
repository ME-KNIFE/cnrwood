<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Order;
use App\Services\CartService;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function __construct(
        private CartService $cartService,
        private OrderService $orderService,
    ) {}

    public function index()
    {
        $cart = $this->cartService->resolveCart();
        $cart->load('items.product.images', 'items.variant');

        if ($cart->items->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('cart_error', 'Sepetiniz boş. Lütfen önce ürün ekleyin.');
        }

        session(['checkout_token' => Str::random(40)]);

        // For authenticated users, load their saved addresses for quick-fill
        $savedAddresses      = collect();
        $defaultShipping     = null;
        $defaultBilling      = null;

        if (auth()->check()) {
            $savedAddresses  = auth()->user()->addresses()->orderByDesc('is_default_shipping')->orderBy('title')->get();
            $defaultShipping = $savedAddresses->firstWhere('is_default_shipping', true);
            $defaultBilling  = $savedAddresses->firstWhere('is_default_billing', true);
        }

        return view('public.checkout', compact('cart', 'savedAddresses', 'defaultShipping', 'defaultBilling'));
    }

    public function store(Request $request)
    {
        // Validate and consume the one-time session token to prevent double-submit
        $sessionToken = session()->pull('checkout_token');
        if (! $sessionToken || $sessionToken !== $request->input('checkout_token')) {
            return redirect()->route('checkout.index')
                ->with('cart_error', 'Form süresi dolmuştur. Lütfen tekrar deneyin.');
        }

        $validated = $request->validate([
            'customer_name'       => ['required', 'string', 'max:255'],
            'customer_email'      => ['required', 'email', 'max:255'],
            'customer_phone'      => ['nullable', 'string', 'max:50'],
            'payment_method'      => ['nullable', 'in:havale_eft,kredi_karti'],
            // Saved-address shortcut (authenticated users only)
            'shipping_address_id' => ['nullable', 'integer'],
            'billing_address_id'  => ['nullable', 'integer'],
            // Manual address entry (required when no saved address used)
            'full_name'           => ['required_without:shipping_address_id', 'nullable', 'string', 'max:255'],
            'phone'               => ['nullable', 'string', 'max:50'],
            'address_line1'       => ['required_without:shipping_address_id', 'nullable', 'string', 'max:500'],
            'address_line2'       => ['nullable', 'string', 'max:500'],
            'city'                => ['required_without:shipping_address_id', 'nullable', 'string', 'max:100'],
            'district'            => ['nullable', 'string', 'max:100'],
            'postal_code'         => ['nullable', 'string', 'max:20'],
        ]);

        $cart = $this->cartService->resolveCart();

        if ($cart->items()->count() === 0) {
            return redirect()->route('cart.index')
                ->with('cart_error', 'Sepetiniz boş.');
        }

        // Resolve shipping address: saved address takes priority for authenticated users
        $shippingAddress = $this->resolveCheckoutAddress(
            $request->filled('shipping_address_id') ? (int) $request->input('shipping_address_id') : null,
            $validated,
        );

        // If caller provided a saved address ID that didn't resolve (IDOR / invalid), abort
        if ($request->filled('shipping_address_id') && $shippingAddress === null) {
            return back()->withInput()->withErrors(['address' => 'Geçersiz teslimat adresi.']);
        }

        if ($shippingAddress === null) {
            return back()->withInput()->withErrors(['address' => 'Teslimat adresi gereklidir.']);
        }

        // Resolve billing address: falls back to shipping when not separately specified
        $billingAddress = $this->resolveCheckoutAddress(
            $request->filled('billing_address_id') ? (int) $request->input('billing_address_id') : null,
            null,
        ) ?? $shippingAddress;

        $paymentMethod = $validated['payment_method'] ?? 'havale_eft';

        $checkoutData = [
            'customer_name'    => $validated['customer_name'],
            'customer_email'   => $validated['customer_email'],
            'customer_phone'   => $validated['customer_phone'] ?? null,
            'payment_method'   => $paymentMethod,
            'shipping_address' => $shippingAddress,
            'billing_address'  => $billingAddress,
        ];

        try {
            $order = $this->orderService->createFromCart($cart, $checkoutData);
        } catch (\RuntimeException $e) {
            return redirect()->route('checkout.index')
                ->withInput()
                ->with('checkout_error', $e->getMessage());
        }

        session(['cart_count' => 0]);

        // Decide the post-order redirect from the order that was actually
        // persisted, not the raw request/local variable — keeps this branch
        // correct even if payment_method is ever normalized/defaulted
        // differently upstream. Order::isKrediKarti()/isHavaleEft() are the
        // single source of truth used everywhere else in the app.
        if ($order->isKrediKarti()) {
            // For credit card: redirect to Iyzico 3DS payment initiation form
            // Store pending order ID for guest auth in PaymentController
            session(['pending_order_id' => $order->id]);

            return view('public.payment-card-form', ['order' => $order]);
        }

        // Havale/EFT (and any other non-card method): order is created with
        // payment_status 'beklemede' (pending bank transfer) by OrderService;
        // send the customer straight to the order-received page — never the
        // card entry form.
        session(['checkout_order_id' => $order->id]);

        return redirect()->route('checkout.success');
    }

    public function success()
    {
        $orderId = session()->pull('checkout_order_id');
        $order   = $orderId ? Order::find($orderId) : null;

        return view('public.order-success', compact('order'));
    }

    /**
     * Resolve a checkout address from either a saved address ID (for authenticated
     * users) or the raw validated field array from the form.
     *
     * Returns null when neither source is available (e.g. billing not provided).
     */
    private function resolveCheckoutAddress(?int $addressId, ?array $validated): ?array
    {
        if ($addressId !== null && auth()->check()) {
            $saved = Address::where('id', $addressId)
                            ->where('user_id', auth()->id())
                            ->first();

            if ($saved) {
                return $saved->toSnapshot();
            }
        }

        if ($validated && ! empty($validated['address_line1'])) {
            return [
                'full_name'     => $validated['full_name'] ?? null,
                'phone'         => $validated['phone'] ?? null,
                'address_line1' => $validated['address_line1'],
                'address_line2' => $validated['address_line2'] ?? null,
                'city'          => $validated['city'],
                'district'      => $validated['district'] ?? null,
                'postal_code'   => $validated['postal_code'] ?? null,
                'country'       => 'Türkiye',
            ];
        }

        return null;
    }
}
