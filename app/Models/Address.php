<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class Address extends Model
{
    use HasFactory;
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
        'is_default_shipping',
        'is_default_billing',
    ];

    protected $casts = [
        'is_default_shipping' => 'boolean',
        'is_default_billing'  => 'boolean',
    ];

    // ── Relationships ─────────────────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ── Default-management helpers ────────────────────────────────────────────

    /**
     * Atomically promote this address as the default shipping address for its owner.
     * Clears the flag on all other addresses in one query, then sets it here.
     */
    public function makeDefaultShipping(): void
    {
        DB::transaction(function () {
            static::where('user_id', $this->user_id)
                  ->where('id', '!=', $this->id)
                  ->update(['is_default_shipping' => false]);

            $this->update(['is_default_shipping' => true]);
        });
    }

    public function makeDefaultBilling(): void
    {
        DB::transaction(function () {
            static::where('user_id', $this->user_id)
                  ->where('id', '!=', $this->id)
                  ->update(['is_default_billing' => false]);

            $this->update(['is_default_billing' => true]);
        });
    }

    /**
     * Returns true if this address is used as a default (shipping or billing)
     * and deleting it would leave the user without any default of that type.
     */
    public function isDeletionBlocked(): bool
    {
        if ($this->is_default_shipping) {
            $hasOther = static::where('user_id', $this->user_id)
                              ->where('id', '!=', $this->id)
                              ->where('is_default_shipping', true)
                              ->exists();
            if (! $hasOther) {
                return true;
            }
        }

        if ($this->is_default_billing) {
            $hasOther = static::where('user_id', $this->user_id)
                              ->where('id', '!=', $this->id)
                              ->where('is_default_billing', true)
                              ->exists();
            if (! $hasOther) {
                return true;
            }
        }

        return false;
    }

    // ── Snapshot ──────────────────────────────────────────────────────────────

    /**
     * Return a JSON-serializable snapshot for embedding in orders.
     * Written to orders.shipping_address / orders.billing_address at checkout time.
     */
    public function toSnapshot(): array
    {
        return [
            'full_name'     => $this->full_name,
            'phone'         => $this->phone,
            'address_line1' => $this->address_line1,
            'address_line2' => $this->address_line2,
            'city'          => $this->city,
            'district'      => $this->district,
            'postal_code'   => $this->postal_code,
            'country'       => $this->country,
        ];
    }
}
