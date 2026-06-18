<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Shipment extends Model
{
    protected $fillable = [
        'order_id',
        'cargo_company',
        'tracking_number',
        'tracking_url',
        'status',
        'shipped_at',
        'estimated_delivery',
        'delivered_at',
        'notes',
    ];

    protected $casts = [
        'shipped_at'         => 'datetime',
        'estimated_delivery' => 'date',
        'delivered_at'       => 'datetime',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function isDelivered(): bool
    {
        return $this->status === 'teslim_edildi';
    }

    public function getStatusLabel(): string
    {
        return match ($this->status) {
            'hazirlanıyor'     => 'Hazırlanıyor',
            'kargoya_verildi'  => 'Kargoya Verildi',
            'teslim_edildi'    => 'Teslim Edildi',
            'iade'             => 'İade',
            default            => $this->status,
        };
    }
}
