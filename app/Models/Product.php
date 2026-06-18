<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Product extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia, HasSlug;

    protected $fillable = [
        'product_category_id',
        'product_type',     // ← THE critical field
        'sku',
        'slug',
        'is_active',
        'is_featured',
        'sort_order',
        'name',
        'description',
        'short_description',
        'meta_title',
        'meta_description',
        'price',
        'compare_at_price',
        'stock_quantity',
        'low_stock_threshold',
        'track_stock',
        'weight_kg',
    ];

    protected $casts = [
        'name'              => 'array',
        'description'       => 'array',
        'short_description' => 'array',
        'meta_title'        => 'array',
        'meta_description'  => 'array',
        'price'             => 'decimal:2',
        'compare_at_price'  => 'decimal:2',
        'is_active'         => 'boolean',
        'is_featured'       => 'boolean',
        'track_stock'       => 'boolean',
    ];

    // ── Slug ─────────────────────────────────────────────────────────────────
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom(fn ($model) => $model->getTranslation('name', 'tr'))
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }

    // ── Media collections ────────────────────────────────────────────────────
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('product_images')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp']);
    }

    // ── Relationships ─────────────────────────────────────────────────────────
    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class)->where('is_active', true);
    }

    // ── TYPE SCOPES ──────────────────────────────────────────────────────────
    // Use these everywhere — never filter product_type manually in controllers.

    /** @return Builder<Product> Only products that can be sold online */
    public function scopeBuyable(Builder $query): Builder
    {
        return $query->where('product_type', 'buyable');
    }

    /** @return Builder<Product> Only products shown in catalog with "Teklif Al" */
    public function scopeQuoteOnly(Builder $query): Builder
    {
        return $query->where('product_type', 'quote_only');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    // ── Helpers ───────────────────────────────────────────────────────────────
    public function isBuyable(): bool
    {
        return $this->product_type === 'buyable';
    }

    public function isQuoteOnly(): bool
    {
        return $this->product_type === 'quote_only';
    }

    /**
     * NEVER call this on a quote_only product.
     * Returns null (not 0) so no template accidentally renders "0 TL".
     */
    public function getDisplayPrice(): ?string
    {
        if ($this->isQuoteOnly() || $this->price === null) {
            return null;
        }

        return number_format($this->price, 2, ',', '.') . ' TL';
    }

    public function isInStock(): bool
    {
        if (! $this->track_stock) {
            return true;
        }

        return ($this->stock_quantity ?? 0) > 0;
    }

    public function isLowStock(): bool
    {
        if (! $this->track_stock) {
            return false;
        }

        return ($this->stock_quantity ?? 0) <= ($this->low_stock_threshold ?? 5)
            && ($this->stock_quantity ?? 0) > 0;
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
