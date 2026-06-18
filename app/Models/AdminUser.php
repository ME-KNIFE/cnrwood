<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class AdminUser extends Authenticatable implements FilamentUser
{
    use Notifiable, SoftDeletes;

    protected $table = 'admin_users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'locale',
        'is_active',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'is_active'     => 'boolean',
        'last_login_at' => 'datetime',
        'password'      => 'hashed',
    ];

    // ── Filament panel access ─────────────────────────────────────────────────

    public function canAccessPanel(Panel $panel): bool
    {
        if (! $this->is_active) {
            return false;
        }

        return match ($panel->getId()) {
            'admin'       => $this->canAccessAdminPanel(),
            'magaza-panel' => $this->canAccessSalesPanel(),
            default       => false,
        };
    }

    public function canAccessAdminPanel(): bool
    {
        return in_array($this->role, [
            'super_admin',
            'sales_manager',
            'editor',
            'product_manager',
            'support',
            // store_manager is explicitly BLOCKED from /admin
        ]);
    }

    public function canAccessSalesPanel(): bool
    {
        return $this->role === 'store_manager';
    }

    // ── Role helpers ──────────────────────────────────────────────────────────

    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    public function isSalesManager(): bool
    {
        return $this->role === 'sales_manager';
    }

    public function isEditor(): bool
    {
        return $this->role === 'editor';
    }

    public function isProductManager(): bool
    {
        return $this->role === 'product_manager';
    }

    public function isSupport(): bool
    {
        return $this->role === 'support';
    }

    public function isStoreManager(): bool
    {
        return $this->role === 'store_manager';
    }

    public function hasRole(string|array $roles): bool
    {
        return in_array($this->role, (array) $roles);
    }

    // ── Scopes ────────────────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeRole($query, string $role)
    {
        return $query->where('role', $role);
    }

    // ── Filament display ──────────────────────────────────────────────────────

    public function getFilamentName(): string
    {
        return $this->name;
    }
}
