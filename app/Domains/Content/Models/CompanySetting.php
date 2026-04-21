<?php

declare(strict_types=1);

namespace App\Domains\Content\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

final class CompanySetting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'type',
    ];

    protected $casts = [
        'type' => 'string',
    ];

    public static function get(string $key, mixed $default = null): mixed
    {
        return Cache::rememberForever("company_setting.{$key}", function () use ($key, $default) {
            $setting = static::where('key', $key)->first();
            
            if ($setting === null) {
                return $default;
            }

            return match ($setting->type) {
                'boolean' => filter_var($setting->value, FILTER_VALIDATE_BOOLEAN),
                'number' => is_numeric($setting->value) ? (float) $setting->value : $default,
                'json' => json_decode($setting->value, true),
                default => $setting->value,
            };
        });
    }

    public static function set(string $key, mixed $value, string $type = 'text'): void
    {
        static::updateOrCreate(
            ['key' => $key],
            [
                'value' => is_array($value) ? json_encode($value) : $value,
                'type' => $type,
            ]
        );

        Cache::forget("company_setting.{$key}");
    }

    public static function forget(string $key): void
    {
        Cache::forget("company_setting.{$key}");
    }
}
