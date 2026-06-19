<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(private CartService $cartService) {}

    public function index()
    {
        $cart = $this->cartService->resolveCart();
        $cart->load('items.product.images', 'items.variant');
        session(['cart_count' => $cart->getItemCount()]);

        return view('public.cart', ['cart' => $cart]);
    }

    public function add(Request $request)
    {
        $data = $request->validate([
            'product_id'         => ['required', 'integer', 'min:1'],
            'quantity'           => ['required', 'integer', 'min:1', 'max:99'],
            'product_variant_id' => ['nullable', 'integer', 'min:1'],
        ]);

        $cart = $this->cartService->resolveCart();

        try {
            $this->cartService->addItem(
                $cart,
                (int) $data['product_id'],
                (int) $data['quantity'],
                isset($data['product_variant_id']) ? (int) $data['product_variant_id'] : null,
            );
        } catch (\RuntimeException $e) {
            return back()->with('cart_error', $e->getMessage());
        }

        session(['cart_count' => $this->cartService->getItemCount($cart)]);

        return redirect()->route('cart.index')->with('cart_success', 'Ürün sepete eklendi.');
    }

    public function update(Request $request, CartItem $item)
    {
        $data = $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:99'],
        ]);

        $cart = $this->cartService->resolveCart();

        try {
            $this->cartService->updateItem($cart, $item, (int) $data['quantity']);
        } catch (\RuntimeException $e) {
            return back()->with('cart_error', $e->getMessage());
        }

        session(['cart_count' => $this->cartService->getItemCount($cart)]);

        return back()->with('cart_success', 'Sepet güncellendi.');
    }

    public function remove(CartItem $item)
    {
        $cart = $this->cartService->resolveCart();

        try {
            $this->cartService->removeItem($cart, $item);
        } catch (\RuntimeException $e) {
            return back()->with('cart_error', $e->getMessage());
        }

        session(['cart_count' => $this->cartService->getItemCount($cart)]);

        return back()->with('cart_success', 'Ürün sepetten kaldırıldı.');
    }

    public function clear()
    {
        $cart = $this->cartService->resolveCart();
        $this->cartService->clearCart($cart);
        session(['cart_count' => 0]);

        return back()->with('cart_success', 'Sepet temizlendi.');
    }
}
