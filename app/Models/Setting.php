<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['group', 'key', 'value', 'type'];

    // ── Static accessor (cached) ──────────────────────────────────────────────

    /**
     * Get a setting value by key.
     * Usage: Setting::get('site_name') or Setting::get('site_name', 'CNRWOOD')
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        $setting = Cache::rememberForever("setting:{$key}", function () use ($key) {
            return static::where('key', $key)->first();
        });

        if (! $setting) {
            return $default;
        }

        return match ($setting->type) {
            'boolean' => (bool) $setting->value,
            'integer' => (int) $setting->value,
            'json'    => json_decode($setting->value, true),
            default   => $setting->value,
        };
    }

    /**
     * Set / upsert a setting and clear cache.
     */
    public static function set(string $key, mixed $value, string $group = 'general', string $type = 'string'): void
    {
        $encoded = is_array($value) ? json_encode($value) : (string) $value;

        static::updateOrCreate(
            ['key' => $key],
            ['value' => $encoded, 'group' => $group, 'type' => $type]
        );

        Cache::forget("setting:{$key}");
    }

    // ── Scopes ────────────────────────────────────────────────────────────────
    public function scopeGroup($query, string $group)
    {
        return $query->where('group', $group);
    }
}
