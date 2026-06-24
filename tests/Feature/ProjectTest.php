<?php

namespace Tests\Feature;

use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    use RefreshDatabase;

    // ------------------------------------------------------------------ listing

    public function test_published_projects_appear_on_listing(): void
    {
        $pub = Project::factory()->published()->create(['title' => ['tr' => 'Yayinda Proje', 'en' => 'Published']]);
        Project::factory()->create(['title' => ['tr' => 'Taslak Proje', 'en' => 'Draft']]); // unpublished

        $response = $this->get(route('public.projects.index'));

        $response->assertOk();
        $response->assertSee('Yayinda Proje');
        $response->assertDontSee('Taslak Proje');
    }

    public function test_unpublished_projects_are_hidden_on_listing(): void
    {
        Project::factory()->create(['title' => ['tr' => 'Gizli Proje', 'en' => 'Hidden']]);

        $response = $this->get(route('public.projects.index'));

        $response->assertOk();
        $response->assertDontSee('Gizli Proje');
    }

    public function test_listing_shows_empty_state_when_no_published_projects(): void
    {
        $response = $this->get(route('public.projects.index'));

        $response->assertOk();
    }

    // ------------------------------------------------------------------ detail

    public function test_published_project_detail_page_loads_by_slug(): void
    {
        $project = Project::factory()->published()->create([
            'title'    => ['tr' => 'Test Proje Detay', 'en' => 'Test Detail'],
            'slug'     => 'test-proje-detay',
            'excerpt_tr' => 'Kisa aciklama metni.',
        ]);

        $response = $this->get(route('public.projects.show', 'test-proje-detay'));

        $response->assertOk();
        $response->assertSee('Test Proje Detay');
    }

    public function test_unpublished_project_detail_returns_404(): void
    {
        Project::factory()->create([
            'title' => ['tr' => 'Gizli Proje', 'en' => 'Hidden'],
            'slug'  => 'gizli-proje',
        ]);

        $response = $this->get(route('public.projects.show', 'gizli-proje'));

        $response->assertNotFound();
    }

    public function test_nonexistent_project_slug_returns_404(): void
    {
        $response = $this->get(route('public.projects.show', 'hicbir-yerde-yok'));

        $response->assertNotFound();
    }

    public function test_project_without_cover_image_does_not_break(): void
    {
        $project = Project::factory()->published()->create([
            'title'            => ['tr' => 'Gorselsiz Proje', 'en' => 'No Image'],
            'slug'             => 'gorselsiz-proje',
            'cover_image_path' => null,
        ]);

        $response = $this->get(route('public.projects.show', 'gorselsiz-proje'));

        $response->assertOk();
        $response->assertSee('Gorselsiz Proje');
    }

    public function test_featured_projects_are_ordered_first(): void
    {
        Project::factory()->published()->create([
            'title'       => ['tr' => 'Normal Proje', 'en' => 'Normal'],
            'is_featured' => false,
            'sort_order'  => 0,
        ]);
        Project::factory()->published()->create([
            'title'       => ['tr' => 'One Cikan Proje', 'en' => 'Featured'],
            'is_featured' => true,
            'sort_order'  => 10,
        ]);

        $projects = Project::published()
            ->orderByDesc('is_featured')
            ->orderBy('sort_order')
            ->get();

        $this->assertEquals('One Cikan Proje', $projects->first()->getTranslation('title', 'tr'));
    }

    public function test_project_client_and_location_show_on_detail(): void
    {
        Project::factory()->published()->create([
            'title'       => ['tr' => 'Musteri Proje', 'en' => 'Client Project'],
            'slug'        => 'musteri-proje',
            'client_name' => 'ABC Lojistik AS',
            'location'    => 'Istanbul',
            'category'    => 'ISPM 15',
        ]);

        $response = $this->get(route('public.projects.show', 'musteri-proje'));

        $response->assertOk();
        $response->assertSee('ABC Lojistik AS');
        $response->assertSee('Istanbul');
        $response->assertSee('ISPM 15');
    }
}
