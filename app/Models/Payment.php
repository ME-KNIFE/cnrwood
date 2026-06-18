<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'order_id',
        'method',
        'status',
        'amount',
        'provider',
        'provider_ref',
        'provider_response',
        'bank_sender_name',
        'bank_sender_iban',
        'paid_at',
        'notes',
    ];

    protected $casts = [
        'amount'            => 'decimal:2',
        'provider_response' => 'array',
        'paid_at'           => 'datetime',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    // ── Status helpers ────────────────────────────────────────────────────────

    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isHavaleEft(): bool
    {
        return $this->method === 'havale_eft';
    }

    public function getStatusLabel(): string
    {
        return match ($this->status) {
            'pending'                => 'Beklemede',
            'awaiting_bank_transfer' => 'Havale Bekleniyor',
            'paid'                   => 'Ödendi',
            'failed'                 => 'Başarısız',
            'cancelled'              => 'İptal Edildi',
            'refunded'               => 'İade Edildi',
            default                  => $this->status,
        };
    }
}
