<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SandikCalculationRequest extends Model
{
    protected $fillable = [
        'quote_request_id',
        'length_cm',
        'width_cm',
        'height_cm',
        'weight_kg',
        'crate_type',
        'requires_ispm15',
        'requires_forklift',
        'requires_crane',
        'shipping_type',
        'material',
        'quantity',
        'destination_city',
        'destination_country',
        'notes',
    ];

    protected $casts = [
        'length_cm'         => 'decimal:2',
        'width_cm'          => 'decimal:2',
        'height_cm'         => 'decimal:2',
        'weight_kg'         => 'decimal:2',
        'requires_ispm15'   => 'boolean',
        'requires_forklift' => 'boolean',
        'requires_crane'    => 'boolean',
    ];

    public function quoteRequest(): BelongsTo
    {
        return $this->belongsTo(QuoteRequest::class);
    }

    public function getVolumeCm3(): float
    {
        return $this->length_cm * $this->width_cm * $this->height_cm;
    }

    public function getDimensionsSummary(): string
    {
        return "{$this->length_cm} × {$this->width_cm} × {$this->height_cm} cm / {$this->weight_kg} kg";
    }

    public function getCrateTypeLabel(): string
    {
        return match ($this->crate_type) {
            'ahsap'          => 'Ahşap Sandık',
            'osb'            => 'OSB Sandık',
            'izgara'         => 'Izgara Palet',
            'vinc_aparatli'  => 'Vinç Aparatlı',
            'endcap'         => 'End Cap',
            'taban_izgara'   => 'Taban Izgara',
            'bilmiyorum'     => 'Bilmiyorum',
            default          => $this->crate_type,
        };
    }
}
