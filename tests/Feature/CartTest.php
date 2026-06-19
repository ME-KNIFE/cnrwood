<?php

namespace Tests\Feature;

use App\Models\Product;
use Tests\TestCase;

class CartTest extends TestCase
{
    public function test_cart_page_returns_200(): void
    {
        $response = $this->get('/sepet');

        $response->assertStatus(200);
    }

    public function test_quote_only_product_cannot_be_added_to_cart(): void
    {
        $product = Product::factory()->quoteOnly()->create();

        $response = $this->post('/sepet/ekle', [
            'product_id' => $product->id,
            'quantity'   => 1,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('cart_error');
    }

    public function test_buyable_product_can_be_added_to_cart(): void
    {
        $product = Product::factory()->buyable()->create();

        $response = $this->post('/sepet/ekle', [
            'product_id' => $product->id,
            'quantity'   => 1,
        ]);

        $response->assertRedirect(route('cart.index'));
        $response->assertSessionHas('cart_success');
    }
}
