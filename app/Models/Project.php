<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Project extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, HasSlug, InteractsWithMedia;

    protected $fillable = [
        // Core
        'slug',
        'status',
        'is_published',
        'is_featured',
        'completed_at',
        'sort_order',
        // Classification
        'category',
        'client_name',
        'location',
        // Cover image (first-party)
        'cover_image_path',
        'image_alt_tr',
        'image_alt_en',
        // Content - flat columns (Phase 15B.1)
        'excerpt_tr',
        'excerpt_en',
        'content_tr',
        'content_en',
        // Legacy JSON columns (keep for backward compat)
        'title',
        'description',
        'meta_title',
        'meta_description',
    ];

    protected $casts = [
        'title'            => 'array',
        'description'      => 'array',
        'meta_title'       => 'array',
        'meta_description' => 'array',
        'completed_at'     => 'date',
        'sort_order'       => 'integer',
        'is_published'     => 'boolean',
        'is_featured'      => 'boolean',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom(fn ($model) => $model->getTranslation('title', 'tr') ?? 'proje')
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('project_gallery');
    }

    /**
     * Published scope: uses is_published boolean (Phase 15B.1).
     * Falls back to legacy status='published' for backward compat.
     */
    public function scopePublished($query)
    {
        return $query->where(function ($q) {
            $q->where('is_published', true)
              ->orWhere('status', 'published');
        });
    }

    /**
     * Returns a public URL for the cover image.
     * Priority: first-party cover_image_path > Spatie MediaLibrary first item > null
     */
    public function getCoverImageUrl(): ?string
    {
        if ($this->cover_image_path) {
            return Storage::disk('public')->url($this->cover_image_path);
        }

        $url = $this->getFirstMediaUrl('project_gallery');
        return $url ?: null;
    }

    /**
     * Get a translated string from a JSON column with locale fallback.
     */
    public function getTranslation(string $field, string $locale): ?string
    {
        $data = $this->$field;

        if (is_array($data)) {
            return $data[$locale] ?? $data['tr'] ?? null;
        }

        return $data;
    }

    /**
     * Get the localised excerpt, falling back to JSON description for legacy records.
     */
    public function getExcerpt(string $locale = 'tr'): ?string
    {
        $flat = $locale === 'en' ? $this->excerpt_en : $this->excerpt_tr;
        if ($flat) {
            return $flat;
        }
        return $this->getTranslation('description', $locale);
    }

    /**
     * Get the localised full content, falling back to excerpt then JSON description.
     */
    public function getContent(string $locale = 'tr'): ?string
    {
        $flat = $locale === 'en' ? $this->content_en : $this->content_tr;
        if ($flat) {
            return $flat;
        }
        return $this->getExcerpt($locale);
    }
}
