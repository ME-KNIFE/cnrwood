<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Address extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'full_name',
        'phone',
        'address_line1',
        'address_line2',
        'city',
        'district',
        'postal_code',
        'country',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Return a JSON-serializable snapshot for embedding in orders.
     * This is what gets written to orders.shipping_address / billing_address.
     */
    public function toSnapshot(): array
    {
        return [
            'full_name'    => $this->full_name,
            'phone'        => $this->phone,
            'address_line1' => $this->address_line1,
            'address_line2' => $this->address_line2,
            'city'         => $this->city,
            'district'     => $this->district,
            'postal_code'  => $this->postal_code,
            'country'      => $this->country,
        ];
    }
}
