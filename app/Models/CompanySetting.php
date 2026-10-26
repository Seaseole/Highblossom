<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Throwable;

#[Fillable(['key', 'value', 'type'])]
/**
 * Key-value company settings with caching and type casting.
 * Maps to the `company_settings` database table.
 *
 * All rows are read once per request into a single aggregate cache entry
 * (`company_settings`) and held in a static in-memory map, so any number of
 * lookups cost at most one cache read (warm) or one query (cold rebuild).
 */
final class CompanySetting extends Model
{
    /**
     * Cache key for the aggregate map of every setting.
     */
    public const CACHE_KEY = 'company_settings';

    /**
     * Container binding holding the per-request resolved map. Scoped to the
     * container (not a true static) so it resets whenever the application is
     * rebuilt — per request under php-fpm, and per test case in the suite.
     */
    private const MEMO_KEY = 'company_settings.resolved';

    protected $casts = [
        'type' => 'string',
    ];

    /**
     * Flush the aggregate cache on any write, so no path (admin, seeder,
     * migration, or direct model save) can leave a stale value behind.
     */
    protected static function booted(): void
    {
        self::saved(static fn () => self::flushCache());
        self::deleted(static fn () => self::flushCache());
    }

    /**
     * Retrieve a setting value by key with type coercion and caching.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        $row = self::resolved()[$key] ?? null;

        if ($row === null) {
            return $default;
        }

        return match ($row['t']) {
            'boolean' => filter_var($row['v'], FILTER_VALIDATE_BOOLEAN),
            'number' => is_numeric($row['v']) ? (float) $row['v'] : $default,
            'json' => json_decode((string) $row['v'], true),
            default => $row['v'],
        };
    }

    /**
     * Store or update a setting value. The saved event flushes the cache.
     */
    public static function set(string $key, mixed $value, string $type = 'text'): void
    {
        self::updateOrCreate(
            ['key' => $key],
            [
                'value' => is_array($value) ? json_encode($value) : $value,
                'type' => $type,
            ]
        );
    }

    /**
     * Drop the settings cache. Over-invalidates by design: the next read
     * rebuilds the whole aggregate in a single query.
     */
    public static function forget(string $key): void
    {
        self::flushCache();
    }

    /**
     * Clear the aggregate cache entry and the per-request memo.
     */
    public static function flushCache(): void
    {
        $app = app();

        if ($app->bound(self::MEMO_KEY)) {
            $app->forgetInstance(self::MEMO_KEY);
        }

        try {
            Cache::forget(self::CACHE_KEY);
        } catch (Throwable) {
            // A missing/unavailable cache store must never break a write.
        }
    }

    /**
     * The raw key => ['v' => value, 't' => type] map for this request.
     *
     * @return array<string, array{v: mixed, t: string|null}>
     */
    private static function resolved(): array
    {
        $app = app();

        if ($app->bound(self::MEMO_KEY)) {
            /** @var array<string, array{v: mixed, t: string|null}> $memo */
            $memo = $app->make(self::MEMO_KEY);

            return $memo;
        }

        $map = self::aggregate();
        $app->instance(self::MEMO_KEY, $map);

        return $map;
    }

    /**
     * Load the aggregate map: cache first, then a lock-guarded rebuild.
     *
     * @return array<string, array{v: mixed, t: string|null}>
     */
    private static function aggregate(): array
    {
        try {
            $cached = Cache::get(self::CACHE_KEY);

            if (is_array($cached)) {
                return $cached;
            }

            // Guard the cold rebuild so a burst of concurrent misses builds once.
            $lock = Cache::lock(self::CACHE_KEY.':lock', 10);

            if ($lock->get()) {
                try {
                    $cached = Cache::get(self::CACHE_KEY);

                    if (is_array($cached)) {
                        return $cached;
                    }

                    $built = self::build();

                    // Only persist a successful read; never cache a failure,
                    // so a missing table during first boot retries next request.
                    if ($built !== null) {
                        Cache::forever(self::CACHE_KEY, $built);
                    }

                    return $built ?? [];
                } finally {
                    $lock->release();
                }
            }

            // Another process holds the lock: read straight from the DB this once.
            return self::build() ?? [];
        } catch (Throwable) {
            return self::build() ?? [];
        }
    }

    /**
     * Read every row in one query. Returns null when the table/cache is
     * unavailable (migrations, first boot) so callers fall back to defaults.
     *
     * @return array<string, array{v: mixed, t: string|null}>|null
     */
    private static function build(): ?array
    {
        try {
            return self::query()
                ->get()
                ->mapWithKeys(static fn (self $setting): array => [
                    $setting->key => ['v' => $setting->value, 't' => $setting->type],
                ])
                ->all();
        } catch (Throwable) {
            return null;
        }
    }
}
