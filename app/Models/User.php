<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

// Replaces the default Laravel User model.
// CNRWOOD additions: phone, type, locale, softDeletes, company/address relationships.
class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'type',     // individual | company
        'locale',   // tr | en
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
    ];

    // ── Relationships ─────────────────────────────────────────────────────────

    public function company(): HasOne
    {
        return $this->hasOne(Company::class);
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class);
    }

    public function defaultShippingAddress(): HasOne
    {
        return $this->hasOne(Address::class)->where('is_default_shipping', true);
    }

    public function defaultBillingAddress(): HasOne
    {
        return $this->hasOne(Address::class)->where('is_default_billing', true);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function quoteRequests(): HasMany
    {
        return $this->hasMany(QuoteRequest::class);
    }

    public function carts(): HasMany
    {
        return $this->hasMany(Cart::class);
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    public function isCompany(): bool
    {
        return $this->type === 'company';
    }
}
