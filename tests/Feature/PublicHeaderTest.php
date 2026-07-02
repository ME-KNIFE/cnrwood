<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

/**
 * Regression coverage for the public header cart/account links fix.
 * Prior to this fix the header had no visible cart or customer login/account
 * links at all — these tests pin down the minimum contract so a future
 * redesign can't silently drop them again.
 */
class PublicHeaderTest extends TestCase
{
    public function test_guest_sees_cart_link_and_login_register_links(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee(route('cart.index'), false);
        $response->assertSee(route('account.login'), false);
        $response->assertSee(route('account.register'), false);
        $response->assertSee('Giriş Yap');
        $response->assertSee('Kayıt Ol');
        $response->assertSee('Sepetim');
    }

    public function test_guest_does_not_see_account_dashboard_or_logout_links(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertDontSee('href="' . route('account.dashboard') . '"', false);
        $response->assertDontSee('href="' . route('account.orders') . '"', false);
        $response->assertDontSee('action="' . route('account.logout') . '"', false);
    }

    public function test_authenticated_user_sees_account_links_and_not_login_links(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/');

        $response->assertOk();
        $response->assertSee(route('cart.index'), false);
        $response->assertSee(route('account.dashboard'), false);
        $response->assertSee(route('account.orders'), false);
        $response->assertSee(route('account.logout'), false);
        $response->assertSee('Hesabım');
        $response->assertSee('Siparişlerim');
        $response->assertSee('Çıkış Yap');
        $response->assertDontSee(route('account.login'), false);
        $response->assertDontSee(route('account.register'), false);
    }

    public function test_header_never_links_customers_to_admin_login(): void
    {
        $guestResponse = $this->get('/');
        $guestResponse->assertOk();
        $guestResponse->assertDontSee('/admin/login', false);
        $guestResponse->assertDontSeeText('admin/login');

        $user = User::factory()->create();
        $authResponse = $this->actingAs($user)->get('/');
        $authResponse->assertOk();
        $authResponse->assertDontSee('/admin/login', false);
    }

    public function test_cart_link_shows_item_count_badge_when_cart_has_items(): void
    {
        $response = $this->withSession(['cart_count' => 3])->get('/');

        $response->assertOk();
        $response->assertSee('cnr-hdr-cart-badge', false);
        $response->assertSee('>3<', false);
    }

    /**
     * Regression test: guest login/register used to render as two separate
     * always-visible top-level nav-style links (class="cnr-hn"), which
     * combined with the cart icon overlapped the centered desktop nav.
     * They must now live inside the single compact #cnr-dd-account
     * dropdown trigger instead.
     */
    public function test_guest_login_register_are_inside_compact_dropdown_not_separate_hn_links(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertDontSee('class="cnr-hn">Giriş Yap', false);
        $response->assertDontSee('class="cnr-hn">Kayıt Ol', false);
        $response->assertSee('id="cnr-acct-trigger"', false);
        $response->assertSee('id="cnr-dd-account"', false);
    }
}
