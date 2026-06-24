<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductImageTest extends TestCase
{
    use RefreshDatabase;

    // -----------------------------------------------------------------------
    // Product listing card
    // -----------------------------------------------------------------------

    public function test_product_card_shows_primary_image_when_present(): void
    {
        $product = Product::factory()->buyable()->create();
        ProductImage::factory()->primary()->create([
            'product_id' => $product->id,
            'url'        => 'product-images/test-primary.jpg',
            'is_active'  => true,
        ]);

        $response = $this->get(route('public.products'));
        $response->assertStatus(200);
        // The primary image URL must appear somewhere in the rendered HTML
        $response->assertSee('product-images/test-primary.jpg', escape: false);
    }

    public function test_product_card_uses_fallback_when_no_images(): void
    {
        $product = Product::factory()->buyable()->create();
        // No images attached

        $response = $this->get(route('public.products'));
        $response->assertStatus(200);
        // SVG placeholder path element should render (no <img> for this product)
        $response->assertSee('M4 16l4.586', escape: false);
    }

    public function test_inactive_image_is_not_shown_publicly(): void
    {
        $product = Product::factory()->buyable()->create();
        ProductImage::factory()->primary()->inactive()->create([
            'product_id' => $product->id,
            'url'        => 'product-images/hidden-image.jpg',
        ]);

        $response = $this->get(route('public.products'));
        $response->assertStatus(200);
        $response->assertDontSee('product-images/hidden-image.jpg', escape: false);
    }

    // -----------------------------------------------------------------------
    // Product detail page
    // -----------------------------------------------------------------------

    public function test_product_detail_shows_primary_image(): void
    {
        $product = Product::factory()->buyable()->create();
        ProductImage::factory()->primary()->create([
            'product_id' => $product->id,
            'url'        => 'product-images/detail-hero.jpg',
            'is_active'  => true,
        ]);

        $response = $this->get(route('public.product', $product->slug));
        $response->assertStatus(200);
        $response->assertSee('product-images/detail-hero.jpg', escape: false);
    }

    public function test_product_detail_uses_placeholder_when_no_active_images(): void
    {
        $product = Product::factory()->buyable()->create();
        // Add an inactive image — should NOT show
        ProductImage::factory()->primary()->inactive()->create([
            'product_id' => $product->id,
            'url'        => 'product-images/inactive.jpg',
        ]);

        $response = $this->get(route('public.product', $product->slug));
        $response->assertStatus(200);
        $response->assertDontSee('product-images/inactive.jpg', escape: false);
    }

    // -----------------------------------------------------------------------
    // Quote-only safety
    // -----------------------------------------------------------------------

    public function test_quote_only_product_page_has_no_price_no_cart(): void
    {
        $product = Product::factory()->quoteOnly()->create();

        $response = $this->get(route('public.product', $product->slug));
        $response->assertStatus(200);

        // Must NOT contain any price rendering or cart form
        $response->assertDontSee('Sepete Ekle');
        $response->assertDontSee('sepet/ekle', escape: false);
        $response->assertDontSee('0,00 TL');
        $response->assertDontSee('0.00 TL');
    }

    public function test_quote_only_product_card_has_no_price_badge(): void
    {
        $product = Product::factory()->quoteOnly()->create();

        $response = $this->get(route('public.products'));
        $response->assertStatus(200);

        // The card must not show any price text for this product
        $response->assertDontSee('0,00 TL');
        $response->assertDontSee('0.00 TL');
        // Must not show "Sepete Ekle" anywhere on the page
        $response->assertDontSee('Sepete Ekle');
    }

    public function test_quote_only_product_cannot_be_added_to_cart(): void
    {
        $product = Product::factory()->quoteOnly()->create();

        $response = $this->post(route('cart.add'), [
            'product_id' => $product->id,
            'quantity'   => 1,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('cart_error');
    }
}
