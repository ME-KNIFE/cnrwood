<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    public function test_checkout_redirects_to_cart_when_empty(): void
    {
        $response = $this->get('/siparis/olustur');

        $response->assertRedirect(route('cart.index'));
    }

    public function test_valid_cart_creates_order(): void
    {
        $user    = User::factory()->create();
        $product = Product::factory()->buyable()->create();

        $this->actingAs($user);

        $this->post('/sepet/ekle', [
            'product_id' => $product->id,
            'quantity'   => 1,
        ]);

        $response = $this->post('/siparis/olustur', $this->checkoutPayload());

        $response->assertRedirect(route('checkout.success'));
        $this->assertDatabaseCount('orders', 1);
        $this->assertDatabaseCount('order_items', 1);
    }

    public function test_checkout_does_not_create_payment_or_shipment(): void
    {
        $user    = User::factory()->create();
        $product = Product::factory()->buyable()->create();

        $this->actingAs($user);

        $this->post('/sepet/ekle', [
            'product_id' => $product->id,
            'quantity'   => 1,
        ]);

        $this->post('/siparis/olustur', $this->checkoutPayload());

        $this->assertDatabaseCount('payments', 0);
        $this->assertDatabaseCount('shipments', 0);
    }

    private function checkoutPayload(): array
    {
        return [
            'customer_name'  => 'Test Müşteri',
            'customer_email' => 'test@example.com',
            'customer_phone' => '05001234567',
            'full_name'      => 'Test Müşteri',
            'phone'          => '05001234567',
            'address_line1'  => 'Test Sokak No: 1',
            'city'           => 'İstanbul',
        ];
    }
}
