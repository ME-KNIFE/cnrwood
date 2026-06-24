<?php

namespace Tests\Feature;

use App\Models\SitePage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SitePageTest extends TestCase
{
    use RefreshDatabase;

    // -------------------------------------------------------------------------
    // Core pages render safely without any DB record
    // -------------------------------------------------------------------------

    public function test_about_page_renders_without_db_record(): void
    {
        $response = $this->get('/hakkimizda');
        $response->assertStatus(200);
        // Hardcoded fallback content must still appear
        $response->assertSee('CNR');
    }

    public function test_corporate_page_renders_without_db_record(): void
    {
        $response = $this->get('/kurumsal');
        $response->assertStatus(200);
        $response->assertSee('CNR');
    }

    public function test_services_page_renders_without_db_record(): void
    {
        $response = $this->get('/hizmetler');
        $response->assertStatus(200);
    }

    // -------------------------------------------------------------------------
    // DB title/content shown when set
    // -------------------------------------------------------------------------

    public function test_about_page_uses_db_title_when_set(): void
    {
        SitePage::create([
            'slug'       => 'hakkimizda',
            'title_tr'   => 'Ozel Hakkimizda Basligi',
            'is_published' => true,
        ]);

        $response = $this->get('/hakkimizda');
        $response->assertStatus(200);
        $response->assertSee('Ozel Hakkimizda Basligi');
    }

    public function test_about_page_uses_db_content_when_set(): void
    {
        SitePage::create([
            'slug'       => 'hakkimizda',
            'content_tr' => 'Bu bir test icerik metnidir.',
            'is_published' => true,
        ]);

        $response = $this->get('/hakkimizda');
        $response->assertStatus(200);
        $response->assertSee('Bu bir test icerik metnidir.');
    }

    public function test_corporate_page_uses_db_excerpt_when_set(): void
    {
        SitePage::create([
            'slug'       => 'kurumsal',
            'excerpt_tr' => 'Kurumsal giris paragraf DB metni.',
            'is_published' => true,
        ]);

        $response = $this->get('/kurumsal');
        $response->assertStatus(200);
        $response->assertSee('Kurumsal giris paragraf DB metni.');
    }

    public function test_services_page_uses_db_excerpt_when_set(): void
    {
        SitePage::create([
            'slug'       => 'hizmetler',
            'excerpt_tr' => 'Hizmetler giris paragraf DB metni.',
            'is_published' => true,
        ]);

        $response = $this->get('/hizmetler');
        $response->assertStatus(200);
        $response->assertSee('Hizmetler giris paragraf DB metni.');
    }

    // -------------------------------------------------------------------------
    // Empty DB fields fall back to hardcoded defaults
    // -------------------------------------------------------------------------

    public function test_about_page_falls_back_when_db_fields_are_empty(): void
    {
        // Record exists but all content fields are null
        SitePage::create([
            'slug'         => 'hakkimizda',
            'title_tr'     => null,
            'excerpt_tr'   => null,
            'content_tr'   => null,
            'is_published' => true,
        ]);

        $response = $this->get('/hakkimizda');
        $response->assertStatus(200);
        // Hardcoded fallback founding story must still be visible
        $response->assertSee('CNR');
    }

    // -------------------------------------------------------------------------
    // Unpublished core pages never return 404 — they fall back to defaults
    // -------------------------------------------------------------------------

    public function test_about_page_does_not_404_when_sitepage_is_unpublished(): void
    {
        SitePage::create([
            'slug'         => 'hakkimizda',
            'title_tr'     => 'Gizli Baslik',
            'is_published' => false,
        ]);

        // Core pages never 404 — the controller fetches by slug regardless of is_published.
        // is_published is an editorial hint used only in the admin panel filter.
        $response = $this->get('/hakkimizda');
        $response->assertStatus(200);
    }

    // -------------------------------------------------------------------------
    // SEO meta fields
    // -------------------------------------------------------------------------

    public function test_about_page_meta_title_uses_db_value(): void
    {
        SitePage::create([
            'slug'         => 'hakkimizda',
            'meta_title_tr' => 'Ozel SEO Basligi',
            'is_published'  => true,
        ]);

        $response = $this->get('/hakkimizda');
        $response->assertStatus(200);
        $response->assertSee('Ozel SEO Basligi');
    }

    // -------------------------------------------------------------------------
    // SitePage model helpers
    // -------------------------------------------------------------------------

    public function test_find_by_slug_returns_null_for_missing_slug(): void
    {
        $page = SitePage::findBySlug('nonexistent-page');
        $this->assertNull($page);
    }

    public function test_find_by_slug_returns_record_when_found(): void
    {
        SitePage::create(['slug' => 'hakkimizda', 'is_published' => true]);
        $page = SitePage::findBySlug('hakkimizda');
        $this->assertNotNull($page);
        $this->assertSame('hakkimizda', $page->slug);
    }

    public function test_get_title_returns_null_when_empty(): void
    {
        $page = SitePage::create(['slug' => 'hakkimizda', 'title_tr' => '', 'is_published' => true]);
        $this->assertNull($page->getTitle('tr'));
    }

    public function test_get_content_returns_null_when_empty(): void
    {
        $page = SitePage::create(['slug' => 'hakkimizda', 'content_tr' => null, 'is_published' => true]);
        $this->assertNull($page->getContent('tr'));
    }
}
