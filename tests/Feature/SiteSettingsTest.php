<?php

namespace Tests\Feature;

use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SiteSettingsTest extends TestCase
{
    use RefreshDatabase;

    // -------------------------------------------------------------------------
    // Setting model helpers
    // -------------------------------------------------------------------------

    public function test_setting_get_returns_default_when_not_set(): void
    {
        $value = Setting::get('phone_primary', '+90 262 751 21 20');
        $this->assertSame('+90 262 751 21 20', $value);
    }

    public function test_setting_set_and_get_roundtrip(): void
    {
        Setting::set('phone_primary', '+90 500 000 00 00', 'contact');
        $value = Setting::get('phone_primary', '+90 262 751 21 20');
        $this->assertSame('+90 500 000 00 00', $value);
    }

    // -------------------------------------------------------------------------
    // Public header reflects settings
    // -------------------------------------------------------------------------

    public function test_header_shows_default_phone_when_no_setting(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('+90 262 751 21 20');
    }

    public function test_header_shows_custom_phone_from_setting(): void
    {
        Setting::set('phone_primary', '+90 532 999 99 99', 'contact');
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('+90 532 999 99 99');
    }

    public function test_header_shows_default_email_when_no_setting(): void
    {
        $response = $this->get('/');
        $response->assertSee('info@cnrwood.com');
    }

    public function test_header_shows_custom_email_from_setting(): void
    {
        Setting::set('email_primary', 'new@cnrwood.com', 'contact');
        $response = $this->get('/');
        $response->assertSee('new@cnrwood.com');
    }

    // -------------------------------------------------------------------------
    // Contact page reflects settings
    // -------------------------------------------------------------------------

    public function test_contact_page_shows_default_address(): void
    {
        $response = $this->get('/iletisim');
        $response->assertStatus(200);
        $response->assertSee('Pelitli Mah. Pelitli Yolu Cad. No: 137/A');
    }

    public function test_contact_page_shows_custom_address_from_setting(): void
    {
        Setting::set('address_street', 'Test Cad. No: 1', 'contact');
        $response = $this->get('/iletisim');
        $response->assertStatus(200);
        $response->assertSee('Test Cad. No: 1');
    }

    public function test_contact_page_maps_link_uses_setting(): void
    {
        Setting::set('maps_link_url', 'https://maps.example.com/cnrwood', 'contact');
        $response = $this->get('/iletisim');
        $response->assertSee('https://maps.example.com/cnrwood', false);
    }
}
