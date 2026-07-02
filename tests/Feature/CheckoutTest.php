<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    private const TOKEN = 'test-checkout-token';

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

        $response = $this->withSession(['checkout_token' => self::TOKEN])
            ->post('/siparis/olustur', $this->checkoutPayload());

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

        $this->withSession(['checkout_token' => self::TOKEN])
            ->post('/siparis/olustur', $this->checkoutPayload());

        $this->assertDatabaseCount('payments', 0);
        $this->assertDatabaseCount('shipments', 0);
    }

    public function test_stale_checkout_token_is_rejected(): void
    {
        $user    = User::factory()->create();
        $product = Product::factory()->buyable()->create();

        $this->actingAs($user);

        $this->post('/sepet/ekle', [
            'product_id' => $product->id,
            'quantity'   => 1,
        ]);

        // POST with a token that doesn't match what's in session (no withSession)
        $response = $this->post('/siparis/olustur', array_merge($this->checkoutPayload(), [
            'checkout_token' => 'wrong-token',
        ]));

        $response->assertRedirect(route('checkout.index'));
        $this->assertDatabaseCount('orders', 0);
    }

    public function test_duplicate_checkout_post_does_not_create_duplicate_orders(): void
    {
        $user    = User::factory()->create();
        $product = Product::factory()->buyable()->create([
            'track_stock'    => true,
            'stock_quantity' => 10,
        ]);

        $this->actingAs($user);

        $this->post('/sepet/ekle', [
            'product_id' => $product->id,
            'quantity'   => 1,
        ]);

        // First POST: token in session matches → order created
        $this->withSession(['checkout_token' => self::TOKEN])
            ->post('/siparis/olustur', $this->checkoutPayload())
            ->assertRedirect(route('checkout.success'));

        // Second POST: token already consumed from session → rejected
        $this->post('/siparis/olustur', $this->checkoutPayload())
            ->assertRedirect(route('checkout.index'));

        $this->assertDatabaseCount('orders', 1);
    }

    public function test_checkout_cannot_oversell_stock(): void
    {
        $user    = User::factory()->create();
        $product = Product::factory()->buyable()->create([
            'track_stock'    => true,
            'stock_quantity' => 1,
        ]);

        $this->actingAs($user);

        $this->post('/sepet/ekle', [
            'product_id' => $product->id,
            'quantity'   => 1,
        ]);

        // Simulate concurrent purchase reducing stock to 0 before checkout completes
        $product->update(['stock_quantity' => 0]);

        $response = $this->withSession(['checkout_token' => self::TOKEN])
            ->post('/siparis/olustur', $this->checkoutPayload());

        $response->assertRedirect(route('checkout.index'));
        $this->assertDatabaseCount('orders', 0);
        $this->assertEquals(0, $product->fresh()->stock_quantity);
    }

    /**
     * Regression test: selecting "Kredi / Banka Kartı" must land the customer
     * on the card-entry view, never on the bank-transfer order-received page.
     */
    public function test_checkout_with_credit_card_redirects_to_card_payment_page(): void
    {
        $user    = User::factory()->create();
        $product = Product::factory()->buyable()->create();

        $this->actingAs($user);

        $this->post('/sepet/ekle', [
            'product_id' => $product->id,
            'quantity'   => 1,
        ]);

        $response = $this->withSession(['checkout_token' => self::TOKEN])
            ->post('/siparis/olustur', $this->checkoutPayload(['payment_method' => 'kredi_karti']));

        // Card flow renders the card-entry view directly (200, not a redirect).
        $response->assertOk();
        $response->assertViewIs('public.payment-card-form');
        $response->assertViewHas('order', fn ($order) => $order->isKrediKarti());
    }

    /**
     * Regression test for the reported production bug: selecting "Havale / EFT"
     * must redirect to the order-received page, never to the card payment view.
     */
    public function test_checkout_with_bank_transfer_redirects_to_order_success(): void
    {
        $user    = User::factory()->create();
        $product = Product::factory()->buyable()->create();

        $this->actingAs($user);

        $this->post('/sepet/ekle', [
            'product_id' => $product->id,
            'quantity'   => 1,
        ]);

        $response = $this->withSession(['checkout_token' => self::TOKEN])
            ->post('/siparis/olustur', $this->checkoutPayload(['payment_method' => 'havale_eft']));

        $response->assertRedirect(route('checkout.success'));

        $order = \App\Models\Order::latest('id')->first();
        $this->assertNotNull($order);
        $this->assertTrue($order->isHavaleEft());
        $this->assertSame('beklemede', $order->payment_status);
    }

    /**
     * The order actually persisted must record whichever payment method the
     * customer picked — this is what the redirect decision is now keyed on.
     */
    public function test_order_stores_the_selected_payment_method(): void
    {
        $cardUser    = User::factory()->create();
        $cardProduct = Product::factory()->buyable()->create();

        $this->actingAs($cardUser);
        $this->post('/sepet/ekle', ['product_id' => $cardProduct->id, 'quantity' => 1]);
        $this->withSession(['checkout_token' => self::TOKEN])
            ->post('/siparis/olustur', $this->checkoutPayload([
                'payment_method' => 'kredi_karti',
                'customer_email' => 'card@example.com',
            ]));

        $this->assertDatabaseHas('orders', [
            'customer_email' => 'card@example.com',
            'payment_method' => 'kredi_karti',
        ]);

        $eftUser    = User::factory()->create();
        $eftProduct = Product::factory()->buyable()->create();

        $this->actingAs($eftUser);
        $this->post('/sepet/ekle', ['product_id' => $eftProduct->id, 'quantity' => 1]);
        $this->withSession(['checkout_token' => self::TOKEN])
            ->post('/siparis/olustur', $this->checkoutPayload([
                'payment_method' => 'havale_eft',
                'customer_email' => 'eft@example.com',
            ]));

        $this->assertDatabaseHas('orders', [
            'customer_email' => 'eft@example.com',
            'payment_method' => 'havale_eft',
        ]);
    }

    private function checkoutPayload(array $overrides = []): array
    {
        return array_merge([
            'checkout_token' => self::TOKEN,
            'customer_name'  => 'Test Müşteri',
            'customer_email' => 'test@example.com',
            'customer_phone' => '05001234567',
            'full_name'      => 'Test Müşteri',
            'phone'          => '05001234567',
            'address_line1'  => 'Test Sokak No: 1',
            'city'           => 'İstanbul',
        ], $overrides);
    }
}
