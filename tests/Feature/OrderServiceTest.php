<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Services\OrderService;
use Tests\TestCase;

class OrderServiceTest extends TestCase
{
    // ── Helpers ───────────────────────────────────────────────────────────────

    private function makeOrder(array $attrs = []): Order
    {
        return Order::factory()->create($attrs);
    }

    // ── confirmHavalePayment ──────────────────────────────────────────────────

    public function test_confirm_havale_payment_sets_paid_and_processing(): void
    {
        $order = $this->makeOrder([
            'status'         => 'beklemede',
            'payment_status' => 'odeme_bekleniyor',
        ]);

        (new OrderService)->confirmHavalePayment($order);

        $fresh = $order->fresh();
        $this->assertEquals('odendi', $fresh->payment_status);
        $this->assertEquals('islemde', $fresh->status);
    }

    // ── cancelOrder ──────────────────────────────────────────────────────────

    public function test_cancel_order_sets_cancelled_and_restores_stock(): void
    {
        $product = Product::factory()->create([
            'track_stock'    => true,
            'stock_quantity' => 10,
        ]);
        $order = $this->makeOrder(['status' => 'beklemede']);
        $order->items()->create([
            'product_id'   => $product->id,
            'product_name' => $product->getTranslation('name', 'tr'),
            'product_sku'  => $product->sku,
            'quantity'     => 3,
            'unit_price'   => '100.00',
            'total_price'  => '300.00',
        ]);

        (new OrderService)->cancelOrder($order, 'Müşteri talebi');

        $this->assertEquals('iptal_edildi', $order->fresh()->status);
        $this->assertNotNull($order->fresh()->cancelled_at);
        $this->assertEquals(13, $product->fresh()->stock_quantity);
    }

    public function test_cancel_order_throws_when_shipped(): void
    {
        $order = $this->makeOrder(['status' => 'kargoya_verildi']);

        $this->expectException(\LogicException::class);

        (new OrderService)->cancelOrder($order);
    }

    public function test_cancel_order_throws_when_delivered(): void
    {
        $order = $this->makeOrder(['status' => 'teslim_edildi']);

        $this->expectException(\LogicException::class);

        (new OrderService)->cancelOrder($order);
    }

    // ── transitionStatus ─────────────────────────────────────────────────────

    public function test_transition_status_rejects_invalid_jump(): void
    {
        $order = $this->makeOrder(['status' => 'beklemede']);

        $this->expectException(\LogicException::class);

        (new OrderService)->transitionStatus($order, 'teslim_edildi');
    }

    public function test_transition_status_to_cancelled_restores_stock(): void
    {
        $product = Product::factory()->create([
            'track_stock'    => true,
            'stock_quantity' => 5,
        ]);
        $order = $this->makeOrder(['status' => 'islemde']);
        $order->items()->create([
            'product_id'   => $product->id,
            'product_name' => $product->getTranslation('name', 'tr'),
            'product_sku'  => $product->sku,
            'quantity'     => 2,
            'unit_price'   => '100.00',
            'total_price'  => '200.00',
        ]);

        (new OrderService)->transitionStatus($order, 'iptal_edildi');

        $this->assertEquals('iptal_edildi', $order->fresh()->status);
        $this->assertEquals(7, $product->fresh()->stock_quantity);
    }

    // ── createFromCart snapshot ───────────────────────────────────────────────

    public function test_checkout_decrements_stock(): void
    {
        $user    = User::factory()->create();
        $product = Product::factory()->buyable()->create([
            'track_stock'    => true,
            'stock_quantity' => 10,
        ]);

        $this->actingAs($user);

        $this->post('/sepet/ekle', [
            'product_id' => $product->id,
            'quantity'   => 2,
        ]);

        $this->post('/siparis/olustur', [
            'customer_name'  => 'Test Müşteri',
            'customer_email' => 'test@example.com',
            'customer_phone' => '05001234567',
            'full_name'      => 'Test Müşteri',
            'phone'          => '05001234567',
            'address_line1'  => 'Test Sokak No: 1',
            'city'           => 'İstanbul',
        ]);

        $this->assertEquals(8, $product->fresh()->stock_quantity);
    }

    public function test_create_from_cart_preserves_customer_and_address_snapshot(): void
    {
        $user    = User::factory()->create();
        $product = Product::factory()->buyable()->create();

        $this->actingAs($user);

        $this->post('/sepet/ekle', ['product_id' => $product->id, 'quantity' => 1]);

        $this->post('/siparis/olustur', [
            'customer_name'  => 'Ahmet Yılmaz',
            'customer_email' => 'ahmet@example.com',
            'customer_phone' => '05559876543',
            'full_name'      => 'Ahmet Yılmaz',
            'phone'          => '05559876543',
            'address_line1'  => 'Atatürk Cad. No:42',
            'city'           => 'Ankara',
        ]);

        $order = Order::first();
        $this->assertNotNull($order);
        $this->assertEquals('Ahmet Yılmaz', $order->customer_name);
        $this->assertEquals('ahmet@example.com', $order->customer_email);
        $this->assertEquals('05559876543', $order->customer_phone);
        $this->assertIsArray($order->shipping_address);
        $this->assertEquals('Ankara', $order->shipping_address['city']);
        $this->assertEquals('Atatürk Cad. No:42', $order->shipping_address['address_line1']);
    }
}
