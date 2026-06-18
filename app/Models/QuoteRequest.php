<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class QuoteRequest extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'reference_number',
        'user_id',
        'assigned_to',
        'type',
        'status',
        'contact_name',
        'contact_email',
        'contact_phone',
        'company_name',
        'tax_number',
        'preferred_contact',
        'message',
        'file_path',
        'file_name',
        'admin_notes',
    ];

    // ── Relationships ─────────────────────────────────────────────────────────
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(AdminUser::class, 'assigned_to');
    }

    public function items(): HasMany
    {
        return $this->hasMany(QuoteRequestItem::class);
    }

    public function sandikCalculation(): HasOne
    {
        return $this->hasOne(SandikCalculationRequest::class);
    }

    // ── Helpers ───────────────────────────────────────────────────────────────
    public function isSandik(): bool
    {
        return $this->type === 'sandik';
    }

    public function isNew(): bool
    {
        return $this->status === 'yeni';
    }

    public function getStatusLabel(): string
    {
        return match ($this->status) {
            'yeni'              => 'Yeni',
            'inceleniyor'       => 'İnceleniyor',
            'teklif_gonderildi' => 'Teklif Gönderildi',
            'kazanildi'         => 'Kazanıldı',
            'kaybedildi'        => 'Kaybedildi',
            default             => $this->status,
        };
    }

    // ── Reference number generator ────────────────────────────────────────────
    public static function generateReference(): string
    {
        $year    = date('Y');
        $latest  = static::whereYear('created_at', $year)->count() + 1;

        return sprintf('TKL-%s-%04d', $year, $latest);
    }

    // ── Scopes ────────────────────────────────────────────────────────────────
    public function scopeNew($query)
    {
        return $query->where('status', 'yeni');
    }

    public function scopeType($query, string $type)
    {
        return $query->where('type', $type);
    }
}
