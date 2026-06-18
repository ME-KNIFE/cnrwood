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
    /**
     * Convert a cart to an order.
     * Called after user confirms checkout (before payment for havale/eft,
     * or after payment intent for kredi_karti).
     */
    public function createFromCart(Cart $cart, array $checkoutData): Order
    {
        return DB::transaction(function () use ($cart, $checkoutData) {
            // Load cart items with products
            $cart->load('items.product', 'coupon');

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

                // Decrement stock
                if ($item->product->track_stock) {
                    $item->product->decrement('stock_quantity', $item->quantity);
                }
            }

            // Clear the cart
            $cart->items()->delete();

            return $order;
        });
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
     */
    public function cancelOrder(Order $order, string $reason = ''): void
    {
        if (in_array($order->status, ['kargoya_verildi', 'teslim_edildi'])) {
            throw new \LogicException("Cannot cancel a shipped or delivered order (#{$order->order_number}).");
        }

        // Restore stock
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
