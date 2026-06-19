<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'order_number',
        'user_id',
        // Customer snapshot
        'customer_name',
        'customer_email',
        'customer_phone',
        // Status
        'status',
        'payment_method',
        'payment_status',
        'payment_provider_ref',
        // Amounts
        'subtotal',
        'discount_amount',
        'shipping_cost',
        'total',
        'coupon_code',
        // Addresses
        'shipping_address',
        'billing_address',
        // Shipping
        'cargo_company',
        'tracking_number',
        'shipped_at',
        'delivered_at',
        'cancelled_at',
        // EFT
        'eft_iban',
        'eft_confirmed_at',
        'eft_confirmed_by',
        // Notes
        'admin_notes',
        'notes',
    ];

    protected $casts = [
        'subtotal'          => 'decimal:2',
        'discount_amount'   => 'decimal:2',
        'shipping_cost'     => 'decimal:2',
        'total'             => 'decimal:2',
        'shipping_address'  => 'array',
        'billing_address'   => 'array',
        'shipped_at'        => 'datetime',
        'delivered_at'      => 'datetime',
        'cancelled_at'      => 'datetime',
        'eft_confirmed_at'  => 'datetime',
    ];

    // ── Relationships ─────────────────────────────────────────────────────────
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function shipments(): HasMany
    {
        return $this->hasMany(Shipment::class);
    }

    public function latestPayment()
    {
        return $this->hasOne(Payment::class)->latestOfMany();
    }

    // ── Status helpers ────────────────────────────────────────────────────────
    public function isPaid(): bool
    {
        return $this->payment_status === 'odendi';
    }

    public function isPending(): bool
    {
        return $this->status === 'beklemede';
    }

    public function isCancelled(): bool
    {
        return $this->status === 'iptal_edildi';
    }

    public function isHavaleEft(): bool
    {
        return $this->payment_method === 'havale_eft';
    }

    public function isKrediKarti(): bool
    {
        return $this->payment_method === 'kredi_karti';
    }

    // ── Scopes ────────────────────────────────────────────────────────────────
    public function scopePending($query)
    {
        return $query->where('status', 'beklemede');
    }

    public function scopeAwaitingPayment($query)
    {
        return $query->where('payment_status', 'beklemede');
    }

    // ── Display helpers ───────────────────────────────────────────────────────
    public function getStatusLabel(): string
    {
        return match ($this->status) {
            'beklemede'           => 'Beklemede',
            'odeme_bekleniyor'    => 'Ödeme Bekleniyor',
            'islemde'             => 'İşlemde',
            'kargoya_verildi'     => 'Kargoya Verildi',
            'teslim_edildi'       => 'Teslim Edildi',
            'iptal_edildi'        => 'İptal Edildi',
            'iade_edildi'         => 'İade Edildi',
            default               => $this->status,
        };
    }
}
