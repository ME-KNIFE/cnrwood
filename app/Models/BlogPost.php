<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class BlogPost extends Model
{
    use SoftDeletes, HasSlug;

    protected $fillable = [
        'author_id',
        'slug',
        'status',
        'published_at',
        'title',
        'excerpt',
        'body',
        'meta_title',
        'meta_description',
        'featured_image_url',
    ];

    protected $casts = [
        'title'            => 'array',
        'excerpt'          => 'array',
        'body'             => 'array',
        'meta_title'       => 'array',
        'meta_description' => 'array',
        'published_at'     => 'datetime',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom(fn ($model) => $model->getTranslation('title', 'tr'))
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function isPublished(): bool
    {
        return $this->status === 'published';
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
