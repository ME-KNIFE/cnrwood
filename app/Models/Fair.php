<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Fair extends Model
{
    use HasSlug;

    protected $fillable = [
        'slug',
        'start_date',
        'end_date',
        'city',
        'venue',
        'sort_order',
        'name',
        'description',
    ];

    protected $casts = [
        'name'        => 'array',
        'description' => 'array',
        'start_date'  => 'date',
        'end_date'    => 'date',
        'sort_order'  => 'integer',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom(fn ($model) => $model->getTranslation('name', 'tr'))
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }

    public function scopeUpcoming($query)
    {
        return $query->where('start_date', '>=', now()->toDateString())
                     ->orderBy('start_date');
    }

    public function scopePast($query)
    {
        return $query->where('start_date', '<', now()->toDateString())
                     ->orderByDesc('start_date');
    }

    public function isUpcoming(): bool
    {
        return $this->start_date->gte(now()->startOfDay());
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
