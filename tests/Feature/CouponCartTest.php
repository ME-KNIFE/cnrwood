<?php

namespace Tests\Feature;

use App\Models\Coupon;
use App\Models\Product;
use App\Models\User;
use Tests\TestCase;

class CouponCartTest extends TestCase
{
    private function makeCoupon(array $attrs = []): Coupon
    {
        return Coupon::create(array_merge([
            'code'      => 'TEST10',
            'type'      => 'percentage',
            'value'     => 10,
            'is_active' => true,
        ], $attrs));
    }

    public function test_apply_valid_coupon_attaches_to_cart(): void
    {
        $this->makeCoupon(['code' => 'SAVE10']);

        $response = $this->post('/sepet/kupon', ['coupon_code' => 'SAVE10']);

        $response->assertRedirect();
        $response->assertSessionHas('cart_success');
    }

    public function test_apply_invalid_coupon_returns_error(): void
    {
        $response = $this->post('/sepet/kupon', ['coupon_code' => 'DOESNOTEXIST']);

        $response->assertRedirect();
        $response->assertSessionHas('cart_error');
    }

    public function test_apply_inactive_coupon_returns_error(): void
    {
        $this->makeCoupon(['code' => 'INACTIVE', 'is_active' => false]);

        $response = $this->post('/sepet/kupon', ['coupon_code' => 'INACTIVE']);

        $response->assertRedirect();
        $response->assertSessionHas('cart_error');
    }

    public function test_apply_expired_coupon_returns_error(): void
    {
        $this->makeCoupon(['code' => 'EXPIRED', 'expires_at' => now()->subDay()]);

        $response = $this->post('/sepet/kupon', ['coupon_code' => 'EXPIRED']);

        $response->assertRedirect();
        $response->assertSessionHas('cart_error');
    }

    public function test_apply_coupon_with_min_amount_too_low_returns_error(): void
    {
        $product = Product::factory()->buyable()->create(['price' => 50]);
        $user    = User::factory()->create();

        $this->actingAs($user);
        $this->post('/sepet/ekle', ['product_id' => $product->id, 'quantity' => 1]);

        $this->makeCoupon(['code' => 'HIGHMIN', 'min_order_amount' => 500]);

        $response = $this->post('/sepet/kupon', ['coupon_code' => 'HIGHMIN']);

        $response->assertRedirect();
        $response->assertSessionHas('cart_error');
    }

    public function test_remove_coupon_clears_cart_coupon(): void
    {
        $this->makeCoupon(['code' => 'CLEAR10']);
        $this->post('/sepet/kupon', ['coupon_code' => 'CLEAR10']);

        $response = $this->post('/sepet/kupon/kaldir');

        $response->assertRedirect();
        $response->assertSessionHas('cart_success');
    }
}
