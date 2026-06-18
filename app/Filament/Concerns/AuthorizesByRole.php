<?php

namespace App\Filament\Concerns;

use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Model;

/**
 * Centralized, declarative role-based access control for Filament resources.
 *
 * Each resource sets four arrays of admin_users.role values:
 *   - $viewRoles    -> may list/view records
 *   - $createRoles  -> may create new records
 *   - $editRoles    -> may edit existing records
 *   - $deleteRoles  -> may delete / bulk-delete records
 *
 * super_admin is intentionally always added by helpers below so a single
 * forgotten override cannot lock out the super admin.
 *
 * Force-delete and restore are gated to super_admin only by default
 * (safest behavior for soft-deleted rows).
 */
trait AuthorizesByRole
{
    // NOTE: The four role arrays below are declared by each consuming resource
    // (not by this trait) to avoid PHP "incompatible trait property" errors.
    // Each resource MUST declare:
    //   protected static array $viewRoles   = [...];
    //   protected static array $createRoles = [...];
    //   protected static array $editRoles   = [...];
    //   protected static array $deleteRoles = [...];
    // super_admin is always allowed by the helpers below regardless of arrays.

    protected static function currentRole(): ?string
    {
        $user = Filament::auth()->user();

        return $user?->role;
    }

    protected static function roleIn(array $roles): bool
    {
        $role = static::currentRole();

        if ($role === null) {
            return false;
        }

        // super_admin always allowed.
        if ($role === 'super_admin') {
            return true;
        }

        return in_array($role, $roles, true);
    }

    public static function canViewAny(): bool
    {
        return static::roleIn(static::$viewRoles);
    }

    public static function canView(Model $record): bool
    {
        return static::canViewAny();
    }

    public static function canCreate(): bool
    {
        return static::roleIn(static::$createRoles);
    }

    public static function canEdit(Model $record): bool
    {
        return static::roleIn(static::$editRoles);
    }

    public static function canDelete(Model $record): bool
    {
        return static::roleIn(static::$deleteRoles);
    }

    public static function canDeleteAny(): bool
    {
        return static::roleIn(static::$deleteRoles);
    }

    /** Force delete and restore are super_admin only. */
    public static function canForceDelete(Model $record): bool
    {
        return static::currentRole() === 'super_admin';
    }

    public static function canForceDeleteAny(): bool
    {
        return static::currentRole() === 'super_admin';
    }

    public static function canRestore(Model $record): bool
    {
        return static::currentRole() === 'super_admin';
    }

    public static function canReorder(): bool
    {
        return static::roleIn(static::$editRoles);
    }
}
