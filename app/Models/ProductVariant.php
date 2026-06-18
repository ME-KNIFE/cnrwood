<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductVariant extends Model
{
    protected $fillable = [
        'product_id',
        'sku',
        'name',
        'price_modifier',
        'stock_quantity',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'name'           => 'array',
        'price_modifier' => 'decimal:2',
        'is_active'      => 'boolean',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Final price = parent product price + modifier.
     * Only valid for buyable products (product will never be quote_only if it has variants).
     */
    public function getFinalPrice(): ?float
    {
        $base = $this->product->price;

        if ($base === null) {
            return null;
        }

        return (float) $base + (float) $this->price_modifier;
    }
}
