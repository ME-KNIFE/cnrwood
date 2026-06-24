<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Fair extends Model
{
    use HasFactory, HasSlug;

    protected $fillable = [
        'slug',
        'start_date',
        'end_date',
        'city',
        'venue',
        'sort_order',
        'is_published',
        'is_featured',
        'cover_image_path',
        'image_alt_tr',
        'image_alt_en',
        'name',
        'description',
    ];

    protected $casts = [
        'name'         => 'array',
        'description'  => 'array',
        'start_date'   => 'date',
        'end_date'     => 'date',
        'sort_order'   => 'integer',
        'is_published' => 'boolean',
        'is_featured'  => 'boolean',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom(fn ($model) => $model->getTranslation('name', 'tr') ?? 'fuar')
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }

    // ---- Scopes -------------------------------------------------------

    /**
     * Published fairs only. Used by all public pages.
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    /**
     * Published fairs starting today or later, ordered soonest first.
     * Featured fairs bubble up within upcoming.
     */
    public function scopeUpcoming($query)
    {
        return $query->where('start_date', '>=', now()->toDateString())
                     ->orderByDesc('is_featured')
                     ->orderBy('start_date');
    }

    /**
     * Published fairs that have already ended, most recent first.
     */
    public function scopePast($query)
    {
        return $query->where('start_date', '<', now()->toDateString())
                     ->orderByDesc('is_featured')
                     ->orderByDesc('start_date');
    }

    // ---- Helpers -------------------------------------------------------

    public function isUpcoming(): bool
    {
        return $this->start_date->gte(now()->startOfDay());
    }

    /**
     * Returns a public URL for the cover image, or null if none.
     */
    public function getCoverImageUrl(): ?string
    {
        if ($this->cover_image_path) {
            return Storage::disk('public')->url($this->cover_image_path);
        }
        return null;
    }

    /**
     * Returns the translated value of a JSON column with locale fallback.
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
     * Return the 4-digit year from start_date, or null.
     */
    public function getYear(): ?int
    {
        return $this->start_date?->year;
    }
}
