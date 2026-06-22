<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Project extends Model implements HasMedia
{
    use SoftDeletes, HasSlug, InteractsWithMedia;

    protected $fillable = [
        'slug',
        'status',
        'completed_at',
        'sort_order',
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
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom(fn ($model) => $model->getTranslation('title', 'tr'))
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('project_gallery');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function getCoverImageUrl(): ?string
    {
        return $this->getFirstMediaUrl('project_gallery') ?: null;
    }

    public function getTranslation(string $field, string $locale): ?string
    {
        $data = $this->$field;

        if (is_array($data)) {
            return $data[$locale] ?? $data['tr'] ?? null;
        }

        return $data;
    }
}
