<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class SitePage extends Model
{
    use HasFactory;

    protected $table = 'site_pages';

    protected $fillable = [
        'slug',
        'title_tr',
        'title_en',
        'excerpt_tr',
        'excerpt_en',
        'content_tr',
        'content_en',
        'hero_image_path',
        'image_alt_tr',
        'image_alt_en',
        'meta_title_tr',
        'meta_title_en',
        'meta_description_tr',
        'meta_description_en',
        'is_published',
        'sort_order',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'sort_order'   => 'integer',
    ];

    // -------------------------------------------------------------------------
    // Finders
    // -------------------------------------------------------------------------

    /**
     * Retrieve a page by slug. Returns null if not found.
     * Views should always have hardcoded fallback content.
     */
    public static function findBySlug(string $slug): ?self
    {
        return static::where('slug', $slug)->first();
    }

    // -------------------------------------------------------------------------
    // Content helpers — prefer DB value, fall through to null so Blade can
    // chain its own ?? fallback.
    // -------------------------------------------------------------------------

    public function getTitle(string $locale = 'tr'): ?string
    {
        $value = $locale === 'en' ? $this->title_en : $this->title_tr;

        return ($value !== null && $value !== '') ? $value : null;
    }

    public function getExcerpt(string $locale = 'tr'): ?string
    {
        $value = $locale === 'en' ? $this->excerpt_en : $this->excerpt_tr;

        return ($value !== null && $value !== '') ? $value : null;
    }

    public function getContent(string $locale = 'tr'): ?string
    {
        $value = $locale === 'en' ? $this->content_en : $this->content_tr;

        return ($value !== null && $value !== '') ? $value : null;
    }

    public function getMetaTitle(string $locale = 'tr'): ?string
    {
        $value = $locale === 'en' ? $this->meta_title_en : $this->meta_title_tr;

        return ($value !== null && $value !== '') ? $value : null;
    }

    public function getMetaDescription(string $locale = 'tr'): ?string
    {
        $value = $locale === 'en' ? $this->meta_description_en : $this->meta_description_tr;

        return ($value !== null && $value !== '') ? $value : null;
    }

    public function getCoverImageUrl(): ?string
    {
        if ($this->hero_image_path && Storage::disk('public')->exists($this->hero_image_path)) {
            return Storage::disk('public')->url($this->hero_image_path);
        }

        return null;
    }

    public function getImageAlt(string $locale = 'tr'): string
    {
        $value = $locale === 'en' ? $this->image_alt_en : $this->image_alt_tr;

        return ($value !== null && $value !== '') ? $value : ($this->getTitle($locale) ?? '');
    }
}
