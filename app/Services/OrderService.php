<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class OrderService
{
    private const ALLOWED_PAYMENT_TRANSITIONS = [
        'beklemede'        => ['odeme_bekleniyor', 'odendi', 'basarisiz', 'iptal_edildi'],
        'odeme_bekleniyor' => ['beklemede', 'odendi', 'basarisiz', 'iptal_edildi'],
        'odendi'           => ['iade_edildi'],
        'basarisiz'        => ['beklemede', 'odeme_bekleniyor'],
        'iptal_edildi'     => [],
        'iade_edildi'      => [],
    ];

    private const ALLOWED_TRANSITIONS = [
        'beklemede'        => ['odeme_bekleniyor', 'islemde', 'iptal_edildi'],
        'odeme_bekleniyor' => ['beklemede', 'islemde', 'iptal_edildi'],
        'islemde'          => ['kargoya_verildi', 'iptal_edildi'],
        'kargoya_verildi'  => ['teslim_edildi'],
        'teslim_edildi'    => [],
        'iptal_edildi'     => [],
        'iade_edildi'      => [],
    ];

    /**
     * Convert a cart to an order.
     * Called after user confirms checkout (before payment for havale/eft,
     * or after payment intent for kredi_karti).
     */
    public function createFromCart(Cart $cart, array $checkoutData): Order
    {
        return DB::transaction(function () use ($cart, $checkoutData) {
            // Load cart items with products
            $cart->load('items.product', 'items.variant', 'coupon');

            // Validate all items are still buyable and in stock
            foreach ($cart->items as $item) {
                $this->validateCartItem($item);
            }

            $subtotal       = $cart->getSubtotal();
            $discountAmount = 0;

            // Apply coupon if valid
            if ($cart->coupon && $cart->coupon->isValid()) {
                $discountAmount = $cart->coupon->calculateDiscount($subtotal);
                $cart->coupon->increment('used_count');
            }

            $shippingCost = $this->calculateShipping($checkoutData['shipping_address'] ?? [], $subtotal);
            $total        = $subtotal - $discountAmount + $shippingCost;

            $user = $cart->user;

            $order = Order::create([
                'order_number'      => $this->generateOrderNumber(),
                'user_id'           => $cart->user_id,
                // Customer snapshot — preserved if user is later deleted
                'customer_name'     => $checkoutData['customer_name'] ?? $user?->name ?? 'Misafir',
                'customer_email'    => $checkoutData['customer_email'] ?? $user?->email ?? '',
                'customer_phone'    => $checkoutData['customer_phone'] ?? $user?->phone ?? null,
                // Status
                'status'            => 'beklemede',
                'payment_method'    => $checkoutData['payment_method'],
                'payment_status'    => 'beklemede',
                // Amounts
                'subtotal'          => $subtotal,
                'discount_amount'   => $discountAmount,
                'shipping_cost'     => $shippingCost,
                'total'             => $total,
                'coupon_code'       => $cart->coupon?->code,
                // Addresses
                'shipping_address'  => $checkoutData['shipping_address'],
                'billing_address'   => $checkoutData['billing_address'] ?? $checkoutData['shipping_address'],
                'notes'             => $checkoutData['notes'] ?? null,
            ]);

            // Create order items (snapshot product data)
            foreach ($cart->items as $item) {
                $order->items()->create([
                    'product_id'         => $item->product_id,
                    'product_variant_id' => $item->product_variant_id,
                    'product_name'       => $item->product->getTranslation('name', 'tr'),
                    'product_sku'        => $item->product->sku,
                    'variant_name'       => $item->variant?->getTranslation('name', 'tr'),
                    'quantity'           => $item->quantity,
                    'unit_price'         => $item->unit_price,
                    'total_price'        => $item->quantity * $item->unit_price,
                ]);

                // Atomic decrement: WHERE guard prevents oversell on concurrent checkouts
                if ($item->product->track_stock) {
                    $decremented = Product::where('id', $item->product_id)
                        ->where('stock_quantity', '>=', $item->quantity)
                        ->decrement('stock_quantity', $item->quantity);

                    if ($decremented === 0) {
                        throw new \RuntimeException(
                            "Stok tükendi: {$item->product->getTranslation('name', 'tr')}"
                        );
                    }
                }
            }

            // Clear the cart
            $cart->items()->delete();

            return $order;
        });
    }

    /**
     * Advance order status through the allowed transition map.
     * Routes iptal_edildi through cancelOrder() so stock is always restored.
     */
    public function transitionStatus(Order $order, string $newStatus): Order
    {
        $current = $order->status;

        if ($current === $newStatus) {
            return $order;
        }

        $allowed = self::ALLOWED_TRANSITIONS[$current] ?? [];
        if (! in_array($newStatus, $allowed, true)) {
            throw new \LogicException("Geçersiz durum geçişi: {$current} → {$newStatus}");
        }

        if ($newStatus === 'iptal_edildi') {
            $this->cancelOrder($order);
        } else {
            $order->update(['status' => $newStatus]);
        }

        return $order->refresh();
    }

    /**
     * Advance payment_status through the allowed transition map.
     * Routes odendi for havale/eft through confirmHavalePayment() so order status
     * is also advanced to islemde in one atomic step.
     */
    public function transitionPaymentStatus(Order $order, string $newPaymentStatus): Order
    {
        $current = $order->payment_status;

        if ($current === $newPaymentStatus) {
            return $order;
        }

        $allowed = self::ALLOWED_PAYMENT_TRANSITIONS[$current] ?? [];
        if (! in_array($newPaymentStatus, $allowed, true)) {
            throw new \LogicException("Geçersiz ödeme durumu geçişi: {$current} → {$newPaymentStatus}");
        }

        if ($newPaymentStatus === 'odendi' && $order->isHavaleEft()) {
            $this->confirmHavalePayment($order);
        } else {
            $order->update(['payment_status' => $newPaymentStatus]);
        }

        return $order->refresh();
    }

    /**
     * Confirm havale/EFT payment (admin manually confirms receipt).
     */
    public function confirmHavalePayment(Order $order): void
    {
        $order->update([
            'payment_status' => 'odendi',
            'status'         => 'islemde',
        ]);
    }

    /**
     * Record online payment result (from iyzico/PayTR webhook).
     */
    public function recordOnlinePaymentResult(Order $order, bool $success, string $providerRef): void
    {
        $order->update([
            'payment_provider_ref' => $providerRef,
            'payment_status'       => $success ? 'odendi' : 'basarisiz',
            'status'               => $success ? 'islemde' : 'beklemede',
        ]);
    }

    /**
     * Cancel an order (only if not yet shipped).
     * Stock restore and status update are atomic inside a DB transaction.
     */
    public function cancelOrder(Order $order, string $reason = ''): void
    {
        if (in_array($order->status, ['kargoya_verildi', 'teslim_edildi'])) {
            throw new \LogicException("Cannot cancel a shipped or delivered order (#{$order->order_number}).");
        }

        DB::transaction(function () use ($order, $reason) {
            foreach ($order->items as $item) {
                if ($item->product?->track_stock) {
                    $item->product->increment('stock_quantity', $item->quantity);
                }
            }

            $order->update([
                'status'       => 'iptal_edildi',
                'cancelled_at' => now(),
                'notes'        => trim(($order->notes ?? '') . "\nİptal: {$reason}"),
            ]);
        });
    }

    // ── Private helpers ───────────────────────────────────────────────────────

    private function validateCartItem(CartItem $item): void
    {
        $product = $item->product;

        if (! $product || ! $product->is_active) {
            throw new \RuntimeException("Ürün artık satışta değil: {$item->product_id}");
        }

        // CRITICAL: Never allow quote_only products in cart
        if ($product->isQuoteOnly()) {
            throw new \RuntimeException("Bu ürün sepete eklenemez (teklif gerektiriyor): {$product->sku}");
        }

        if ($product->track_stock && ($product->stock_quantity ?? 0) < $item->quantity) {
            throw new \RuntimeException("Yetersiz stok: {$product->getTranslation('name', 'tr')}");
        }
    }

    private function calculateShipping(array $address, float $subtotal): float
    {
        // Placeholder — real logic in Phase 7
        // Free shipping over 1500 TL domestic
        if ($subtotal >= 1500) {
            return 0;
        }

        return 0; // TBD: connect to shipping provider API
    }

    private function generateOrderNumber(): string
    {
        $year    = date('Y');
        $latest  = Order::whereYear('created_at', $year)->count() + 1;

        return sprintf('SIP-%s-%05d', $year, $latest);
    }
}
