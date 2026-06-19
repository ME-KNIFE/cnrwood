<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\CartService;
use App\Services\OrderService;
use Illuminate\Http\Request;

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

        return view('public.checkout', compact('cart'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name'  => ['required', 'string', 'max:255'],
            'customer_email' => ['required', 'email', 'max:255'],
            'customer_phone' => ['nullable', 'string', 'max:50'],
            'full_name'      => ['required', 'string', 'max:255'],
            'phone'          => ['nullable', 'string', 'max:50'],
            'address_line1'  => ['required', 'string', 'max:500'],
            'address_line2'  => ['nullable', 'string', 'max:500'],
            'city'           => ['required', 'string', 'max:100'],
            'district'       => ['nullable', 'string', 'max:100'],
            'postal_code'    => ['nullable', 'string', 'max:20'],
        ]);

        $cart = $this->cartService->resolveCart();

        if ($cart->items()->count() === 0) {
            return redirect()->route('cart.index')
                ->with('cart_error', 'Sepetiniz boş.');
        }

        $shippingAddress = [
            'full_name'     => $validated['full_name'],
            'phone'         => $validated['phone'] ?? null,
            'address_line1' => $validated['address_line1'],
            'address_line2' => $validated['address_line2'] ?? null,
            'city'          => $validated['city'],
            'district'      => $validated['district'] ?? null,
            'postal_code'   => $validated['postal_code'] ?? null,
            'country'       => 'Türkiye',
        ];

        $checkoutData = [
            'customer_name'    => $validated['customer_name'],
            'customer_email'   => $validated['customer_email'],
            'customer_phone'   => $validated['customer_phone'] ?? null,
            'payment_method'   => 'havale_eft',
            'shipping_address' => $shippingAddress,
        ];

        try {
            $order = $this->orderService->createFromCart($cart, $checkoutData);
        } catch (\RuntimeException $e) {
            return redirect()->route('checkout.index')
                ->withInput()
                ->with('checkout_error', $e->getMessage());
        }

        session(['checkout_order_id' => $order->id]);
        session(['cart_count' => 0]);

        return redirect()->route('checkout.success');
    }

    public function success()
    {
        $orderId = session()->pull('checkout_order_id');
        $order   = $orderId ? Order::find($orderId) : null;

        return view('public.order-success', compact('order'));
    }
}
