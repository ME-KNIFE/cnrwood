<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Tests\TestCase;

class AccountTest extends TestCase
{
    // ── Auth ──────────────────────────────────────────────────────────────────

    public function test_guest_is_redirected_from_dashboard(): void
    {
        $this->get('/hesabim')->assertRedirect('/hesabim/giris');
    }

    public function test_guest_is_redirected_from_orders(): void
    {
        $this->get('/hesabim/siparislerim')->assertRedirect('/hesabim/giris');
    }

    public function test_customer_can_register(): void
    {
        $response = $this->post('/hesabim/kayit', [
            'name'                  => 'Test Müşteri',
            'email'                 => 'yeni@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('account.dashboard'));
        $this->assertDatabaseHas('users', ['email' => 'yeni@example.com']);
    }

    public function test_customer_cannot_register_with_duplicate_email(): void
    {
        User::factory()->create(['email' => 'var@example.com']);

        $response = $this->post('/hesabim/kayit', [
            'name'                  => 'Duplikat',
            'email'                 => 'var@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_customer_can_login(): void
    {
        $user = User::factory()->create(['password' => bcrypt('password123')]);

        $response = $this->post('/hesabim/giris', [
            'email'    => $user->email,
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('account.dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_login_fails_with_wrong_password(): void
    {
        $user = User::factory()->create(['password' => bcrypt('correct')]);

        $this->post('/hesabim/giris', [
            'email'    => $user->email,
            'password' => 'wrong',
        ])->assertSessionHasErrors('email');
    }

    public function test_customer_can_logout(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->post('/hesabim/cikis')->assertRedirect(route('home'));
        $this->assertGuest();
    }

    // ── Dashboard ─────────────────────────────────────────────────────────────

    public function test_authenticated_customer_can_view_dashboard(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
             ->get('/hesabim')
             ->assertOk()
             ->assertSee($user->name);
    }

    // ── Order history ─────────────────────────────────────────────────────────

    public function test_customer_can_view_own_orders(): void
    {
        $user  = User::factory()->create();
        $order = Order::factory()->for($user)->create();

        $this->actingAs($user)
             ->get('/hesabim/siparislerim')
             ->assertOk()
             ->assertSee($order->order_number);
    }

    public function test_customer_cannot_see_other_users_orders(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $order = Order::factory()->for($user2)->create();

        // user1 hits the orders list — user2's order must not appear
        $response = $this->actingAs($user1)->get('/hesabim/siparislerim');
        $response->assertOk();
        $response->assertDontSee($order->order_number);
    }

    // ── IDOR prevention ───────────────────────────────────────────────────────

    public function test_customer_cannot_access_another_users_order_detail(): void
    {
        $owner    = User::factory()->create();
        $attacker = User::factory()->create();
        $order    = Order::factory()->for($owner)->create();

        $this->actingAs($attacker)
             ->get("/hesabim/siparislerim/{$order->id}")
             ->assertForbidden();
    }

    public function test_customer_can_access_own_order_detail(): void
    {
        $user  = User::factory()->create();
        $order = Order::factory()->for($user)->create();

        $this->actingAs($user)
             ->get("/hesabim/siparislerim/{$order->id}")
             ->assertOk()
             ->assertSee($order->order_number);
    }

    // ── Profile ───────────────────────────────────────────────────────────────

    public function test_customer_can_update_profile(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
             ->patch('/hesabim/profil', [
                 'name'  => 'Yeni Ad',
                 'email' => $user->email,
                 'phone' => '05001112233',
             ])
             ->assertRedirect(route('account.profile'));

        $this->assertEquals('Yeni Ad', $user->fresh()->name);
        $this->assertEquals('05001112233', $user->fresh()->phone);
    }

    public function test_profile_update_rejects_duplicate_email(): void
    {
        $user1 = User::factory()->create(['email' => 'first@example.com']);
        $user2 = User::factory()->create(['email' => 'second@example.com']);

        $this->actingAs($user1)
             ->patch('/hesabim/profil', [
                 'name'  => $user1->name,
                 'email' => 'second@example.com',
             ])
             ->assertSessionHasErrors('email');
    }

    // ── Address CRUD ──────────────────────────────────────────────────────────

    public function test_customer_can_add_address(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
             ->post('/hesabim/adresler', $this->addressPayload())
             ->assertRedirect(route('account.addresses'));

        $this->assertDatabaseHas('addresses', ['user_id' => $user->id, 'title' => 'Ev']);
    }

    public function test_first_address_becomes_default_shipping_and_billing(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post('/hesabim/adresler', $this->addressPayload());

        $address = $user->addresses()->first();
        $this->assertTrue($address->is_default_shipping);
        $this->assertTrue($address->is_default_billing);
    }

    public function test_customer_can_edit_address(): void
    {
        $user    = User::factory()->create();
        $address = Address::factory()->for($user)->create();

        $this->actingAs($user)
             ->put("/hesabim/adresler/{$address->id}", array_merge($this->addressPayload(), ['title' => 'İş']))
             ->assertRedirect(route('account.addresses'));

        $this->assertEquals('İş', $address->fresh()->title);
    }

    public function test_customer_cannot_edit_another_users_address(): void
    {
        $owner    = User::factory()->create();
        $attacker = User::factory()->create();
        $address  = Address::factory()->for($owner)->create();

        $this->actingAs($attacker)
             ->put("/hesabim/adresler/{$address->id}", $this->addressPayload())
             ->assertForbidden();
    }

    public function test_customer_can_delete_non_default_address(): void
    {
        $user    = User::factory()->create();
        $address = Address::factory()->for($user)->create([
            'is_default_shipping' => false,
            'is_default_billing'  => false,
        ]);

        $this->actingAs($user)
             ->delete("/hesabim/adresler/{$address->id}")
             ->assertRedirect(route('account.addresses'));

        $this->assertDatabaseMissing('addresses', ['id' => $address->id]);
    }

    public function test_customer_cannot_delete_default_shipping_address_when_it_is_the_only_one(): void
    {
        $user    = User::factory()->create();
        $address = Address::factory()->for($user)->create([
            'is_default_shipping' => true,
            'is_default_billing'  => false,
        ]);

        $this->actingAs($user)
             ->delete("/hesabim/adresler/{$address->id}")
             ->assertRedirect(route('account.addresses'));

        // Address must still exist
        $this->assertDatabaseHas('addresses', ['id' => $address->id]);
    }

    public function test_customer_cannot_delete_another_users_address(): void
    {
        $owner    = User::factory()->create();
        $attacker = User::factory()->create();
        $address  = Address::factory()->for($owner)->create(['is_default_shipping' => false, 'is_default_billing' => false]);

        $this->actingAs($attacker)
             ->delete("/hesabim/adresler/{$address->id}")
             ->assertForbidden();
    }

    // ── Default address logic ─────────────────────────────────────────────────

    public function test_setting_default_shipping_unsets_previous(): void
    {
        $user  = User::factory()->create();
        $addr1 = Address::factory()->for($user)->create(['is_default_shipping' => true]);
        $addr2 = Address::factory()->for($user)->create(['is_default_shipping' => false]);

        $this->actingAs($user)
             ->post("/hesabim/adresler/{$addr2->id}/teslimat")
             ->assertRedirect(route('account.addresses'));

        $this->assertFalse($addr1->fresh()->is_default_shipping);
        $this->assertTrue($addr2->fresh()->is_default_shipping);
    }

    public function test_setting_default_billing_unsets_previous(): void
    {
        $user  = User::factory()->create();
        $addr1 = Address::factory()->for($user)->create(['is_default_billing' => true]);
        $addr2 = Address::factory()->for($user)->create(['is_default_billing' => false]);

        $this->actingAs($user)
             ->post("/hesabim/adresler/{$addr2->id}/faturalama")
             ->assertRedirect(route('account.addresses'));

        $this->assertFalse($addr1->fresh()->is_default_billing);
        $this->assertTrue($addr2->fresh()->is_default_billing);
    }

    public function test_cannot_set_another_users_address_as_default(): void
    {
        $owner    = User::factory()->create();
        $attacker = User::factory()->create();
        $address  = Address::factory()->for($owner)->create();

        $this->actingAs($attacker)
             ->post("/hesabim/adresler/{$address->id}/teslimat")
             ->assertForbidden();
    }

    // ── Checkout address selection ────────────────────────────────────────────

    public function test_checkout_uses_saved_address_when_id_provided(): void
    {
        $user    = User::factory()->create();
        $product = Product::factory()->buyable()->create();
        $address = Address::factory()->for($user)->create([
            'is_default_shipping' => true,
            'is_default_billing'  => true,
        ]);

        $this->actingAs($user);
        $this->post('/sepet/ekle', ['product_id' => $product->id, 'quantity' => 1]);

        $this->withSession(['checkout_token' => 'tok'])
             ->post('/siparis/olustur', [
                 'checkout_token'      => 'tok',
                 'customer_name'       => $user->name,
                 'customer_email'      => $user->email,
                 'shipping_address_id' => $address->id,
             ])
             ->assertRedirect(route('checkout.success'));

        $order = Order::first();
        $this->assertEquals($address->city, $order->shipping_address['city']);
        $this->assertEquals($address->city, $order->billing_address['city']);
    }

    public function test_checkout_rejects_address_belonging_to_another_user(): void
    {
        $attacker = User::factory()->create();
        $victim   = User::factory()->create();
        $product  = Product::factory()->buyable()->create();
        $address  = Address::factory()->for($victim)->create([
            'is_default_shipping' => true,
        ]);

        $this->actingAs($attacker);
        $this->post('/sepet/ekle', ['product_id' => $product->id, 'quantity' => 1]);

        // Supplying another user's address_id — checkout must ignore it (no address_line1 fallback either)
        $response = $this->withSession(['checkout_token' => 'tok'])
             ->post('/siparis/olustur', [
                 'checkout_token'      => 'tok',
                 'customer_name'       => $attacker->name,
                 'customer_email'      => $attacker->email,
                 'shipping_address_id' => $address->id,
                 // No manual address fields provided
             ]);

        // Should fail validation (address_line1 required_without shipping_address_id that resolves to null)
        $response->assertSessionHasErrors();
        $this->assertDatabaseCount('orders', 0);
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    private function addressPayload(): array
    {
        return [
            'title'         => 'Ev',
            'full_name'     => 'Test Kullanıcı',
            'phone'         => '05001234567',
            'address_line1' => 'Test Mahallesi No:1',
            'city'          => 'İstanbul',
            'district'      => 'Kadıköy',
            'postal_code'   => '34700',
            'country'       => 'Türkiye',
        ];
    }
}
