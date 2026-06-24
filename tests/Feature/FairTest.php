<?php

namespace Tests\Feature;

use App\Models\Fair;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FairTest extends TestCase
{
    use RefreshDatabase;

    // ------------------------------------------------------------------ listing

    public function test_published_fairs_appear_on_listing(): void
    {
        Fair::factory()->published()->upcoming()->create([
            'name' => ['tr' => 'Yayinda Fuar', 'en' => 'Published Fair'],
            'slug' => 'yayinda-fuar',
        ]);
        Fair::factory()->upcoming()->create([
            'name' => ['tr' => 'Taslak Fuar', 'en' => 'Draft Fair'],
            'slug' => 'taslak-fuar',
        ]);

        $response = $this->get(route('public.fairs.index'));

        $response->assertOk();
        $response->assertSee('Yayinda Fuar');
        $response->assertDontSee('Taslak Fuar');
    }

    public function test_unpublished_fairs_are_hidden_on_listing(): void
    {
        Fair::factory()->upcoming()->create([
            'name' => ['tr' => 'Gizli Fuar', 'en' => 'Hidden Fair'],
            'slug' => 'gizli-fuar',
        ]);

        $response = $this->get(route('public.fairs.index'));

        $response->assertOk();
        $response->assertDontSee('Gizli Fuar');
    }

    public function test_listing_shows_empty_state_when_no_published_fairs(): void
    {
        $response = $this->get(route('public.fairs.index'));

        $response->assertOk();
    }

    // ------------------------------------------------------------------ detail

    public function test_published_fair_detail_page_loads_by_slug(): void
    {
        Fair::factory()->published()->upcoming()->create([
            'name' => ['tr' => 'Detay Fuari', 'en' => 'Detail Fair'],
            'slug' => 'detay-fuari',
        ]);

        $response = $this->get(route('public.fairs.show', 'detay-fuari'));

        $response->assertOk();
        $response->assertSee('Detay Fuari');
    }

    public function test_unpublished_fair_detail_returns_404(): void
    {
        Fair::factory()->upcoming()->create([
            'name' => ['tr' => 'Gizli Fuar', 'en' => 'Hidden Fair'],
            'slug' => 'gizli-fuar-detay',
        ]);

        $response = $this->get(route('public.fairs.show', 'gizli-fuar-detay'));

        $response->assertNotFound();
    }

    public function test_nonexistent_fair_slug_returns_404(): void
    {
        $response = $this->get(route('public.fairs.show', 'olmayan-fuar'));

        $response->assertNotFound();
    }

    public function test_fair_without_cover_image_does_not_break(): void
    {
        Fair::factory()->published()->upcoming()->create([
            'name'             => ['tr' => 'Gorselsiz Fuar', 'en' => 'No Image Fair'],
            'slug'             => 'gorselsiz-fuar',
            'cover_image_path' => null,
        ]);

        $response = $this->get(route('public.fairs.show', 'gorselsiz-fuar'));

        $response->assertOk();
        $response->assertSee('Gorselsiz Fuar');
    }

    public function test_featured_fairs_are_ordered_first_in_upcoming(): void
    {
        Fair::factory()->published()->upcoming()->create([
            'name'        => ['tr' => 'Normal Fuar', 'en' => 'Normal'],
            'slug'        => 'normal-fuar',
            'is_featured' => false,
            'start_date'  => now()->addDays(5)->format('Y-m-d'),
        ]);
        Fair::factory()->published()->upcoming()->create([
            'name'        => ['tr' => 'One Cikan Fuar', 'en' => 'Featured'],
            'slug'        => 'one-cikan-fuar',
            'is_featured' => true,
            'start_date'  => now()->addDays(30)->format('Y-m-d'),
        ]);

        $fairs = Fair::published()->upcoming()->get();

        $this->assertEquals('One Cikan Fuar', $fairs->first()->getTranslation('name', 'tr'));
    }

    public function test_fair_detail_shows_venue_and_city(): void
    {
        Fair::factory()->published()->upcoming()->create([
            'name'   => ['tr' => 'Sehirli Fuar', 'en' => 'City Fair'],
            'slug'   => 'sehirli-fuar',
            'city'   => 'Istanbul',
            'venue'  => 'Tuyap Fuar Merkezi',
        ]);

        $response = $this->get(route('public.fairs.show', 'sehirli-fuar'));

        $response->assertOk();
        $response->assertSee('Istanbul');
        $response->assertSee('Tuyap Fuar Merkezi');
    }
}
