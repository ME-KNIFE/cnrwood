<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\DB;

class CartService
{
    public function resolveCart(): Cart
    {
        if (auth()->check()) {
            return Cart::firstOrCreate(['user_id' => auth()->id()]);
        }

        return Cart::firstOrCreate(['session_id' => session()->getId()]);
    }

    public function addItem(Cart $cart, int $productId, int $qty, ?int $variantId): CartItem
    {
        [$product, $variant, $price] = $this->validateProduct($productId, $variantId, $qty);

        return DB::transaction(function () use ($cart, $product, $variant, $variantId, $qty, $price) {
            // Eloquent generates IS NULL when $variantId is null — handles the MySQL
            // NULL-in-unique-index gap where the DB constraint won't deduplicate.
            $item = CartItem::where('cart_id', $cart->id)
                ->where('product_id', $product->id)
                ->where('product_variant_id', $variantId)
                ->first();

            if ($item) {
                $newQty = min($item->quantity + $qty, 99);

                if ($product->track_stock) {
                    $available = $variant ? $variant->stock_quantity : ($product->stock_quantity ?? 0);
                    if ($newQty > $available) {
                        throw new \RuntimeException('Stok miktarı yetersiz.');
                    }
                }

                $item->quantity = $newQty;
                $item->save();
            } else {
                $item = CartItem::create([
                    'cart_id'            => $cart->id,
                    'product_id'         => $product->id,
                    'product_variant_id' => $variantId,
                    'quantity'           => $qty,
                    'unit_price'         => $price,
                ]);
            }

            return $item;
        });
    }

    public function updateItem(Cart $cart, CartItem $item, int $qty): CartItem
    {
        if ($item->cart_id !== $cart->id) {
            throw new \RuntimeException('Bu işlem için yetkiniz yok.');
        }

        $product = Product::active()->buyable()->find($item->product_id);

        if (! $product) {
            throw new \RuntimeException('Ürün artık satışta değil.');
        }

        if ($product->track_stock) {
            $variant   = $item->product_variant_id
                ? ProductVariant::where('id', $item->product_variant_id)
                    ->where('product_id', $product->id)
                    ->where('is_active', true)
                    ->first()
                : null;
            $available = $variant ? $variant->stock_quantity : ($product->stock_quantity ?? 0);

            if ($qty > $available) {
                throw new \RuntimeException('Stok miktarı yetersiz.');
            }
        }

        $item->quantity = $qty;
        $item->save();

        return $item;
    }

    public function removeItem(Cart $cart, CartItem $item): void
    {
        if ($item->cart_id !== $cart->id) {
            throw new \RuntimeException('Bu işlem için yetkiniz yok.');
        }

        $item->delete();
    }

    public function clearCart(Cart $cart): void
    {
        $cart->items()->delete();
    }

    public function getItemCount(Cart $cart): int
    {
        return (int) $cart->items()->sum('quantity');
    }

    public function applyCoupon(Cart $cart, string $code): void
    {
        $coupon = Coupon::where('code', strtoupper(trim($code)))->first();

        if (! $coupon || ! $coupon->isValid()) {
            throw new \RuntimeException('Geçersiz veya süresi dolmuş kupon kodu.');
        }

        $cart->loadMissing('items');
        $subtotal = $cart->getSubtotal();

        if ($coupon->min_order_amount && $subtotal < (float) $coupon->min_order_amount) {
            throw new \RuntimeException(
                'Bu kupon için minimum sipariş tutarı ' . number_format((float) $coupon->min_order_amount, 2, ',', '.') . ' TL\'dir.'
            );
        }

        $cart->update(['coupon_id' => $coupon->id]);
    }

    public function removeCoupon(Cart $cart): void
    {
        $cart->update(['coupon_id' => null]);
    }

    // ── Private ───────────────────────────────────────────────────────────────

    /**
     * @return array{0: Product, 1: ProductVariant|null, 2: float}
     *
     * @throws \RuntimeException on invalid product, variant, price, or stock.
     */
    private function validateProduct(int $productId, ?int $variantId, int $qty): array
    {
        $product = Product::active()->buyable()->find($productId);

        if (! $product) {
            throw new \RuntimeException('Ürün bulunamadı veya sepete eklenemez.');
        }

        if ($variantId === null && $product->variants()->where('is_active', true)->exists()) {
            throw new \RuntimeException('Bu ürün için bir seçenek seçmelisiniz.');
        }

        $variant = null;
        $price   = (float) $product->price;

        if ($variantId !== null) {
            $variant = ProductVariant::where('id', $variantId)
                ->where('product_id', $product->id)
                ->where('is_active', true)
                ->first();

            if (! $variant) {
                throw new \RuntimeException('Seçilen ürün seçeneği geçerli değil.');
            }

            $finalPrice = $variant->getFinalPrice();
            if ($finalPrice !== null) {
                $price = $finalPrice;
            }
        }

        if ($price <= 0) {
            throw new \RuntimeException('Bu ürün için fiyat bilgisi mevcut değil.');
        }

        if ($product->track_stock) {
            $available = $variant ? $variant->stock_quantity : ($product->stock_quantity ?? 0);

            if ($available <= 0) {
                throw new \RuntimeException('Bu ürün stokta yok.');
            }

            if ($qty > $available) {
                throw new \RuntimeException('Yeterli stok yok. Mevcut: ' . $available . ' adet.');
            }
        }

        return [$product, $variant, $price];
    }
}
