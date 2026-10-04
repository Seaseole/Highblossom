<?php

declare(strict_types=1);

namespace App\Services;

use App\Actions\Seo\BuildSitemap;
use App\Actions\Seo\GenerateRobotsTxt;
use App\Models\CompanySetting;
use App\Services\Settings\SettingsManager;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use InvalidArgumentException;
use Spatie\Permission\PermissionRegistrar;

/**
 * Inspect, clear, and optimize the application's cache groups.
 *
 * Groups are a fixed registry; user input only ever selects a group by
 * its allowlisted key, never a raw cache key or path.
 */
final class CacheManagerService
{
    public function __construct(
        private readonly SettingsManager $settings,
        private readonly ApplicationVersionService $versions,
    ) {}

    /**
     * The cache group registry.
     *
     * @return array<string, array{label: string, description: string, optimizable: bool}>
     */
    public function groups(): array
    {
        return [
            'settings' => [
                'label' => 'Company Settings',
                'description' => 'Cached company setting values used across the site.',
                'optimizable' => true,
            ],
            'seo' => [
                'label' => 'SEO Output',
                'description' => 'Rendered sitemap.xml and robots.txt responses.',
                'optimizable' => true,
            ],
            'versions' => [
                'label' => 'Application Version',
                'description' => 'Cached pointer to the current application release.',
                'optimizable' => true,
            ],
            'app' => [
                'label' => 'Application Cache',
                'description' => 'All remaining cached entries. Database sessions are unaffected.',
                'optimizable' => false,
            ],
            'framework' => [
                'label' => 'Framework Cache',
                'description' => 'Config, routes, events, and compiled Blade views.',
                'optimizable' => true,
            ],
        ];
    }

    /**
     * The allowlisted group keys.
     *
     * @return list<string>
     */
    public function groupKeys(): array
    {
        return array_keys($this->groups());
    }

    /**
     * The group keys that support an optimize (warm/rebuild) action.
     *
     * @return list<string>
     */
    public function optimizableGroupKeys(): array
    {
        return array_keys(array_filter($this->groups(), fn (array $group): bool => $group['optimizable']));
    }

    /**
     * Stats for every group, ready for the admin table.
     *
     * @return list<array<string, mixed>>
     */
    public function stats(): array
    {
        return collect($this->groups())
            ->map(fn (array $group, string $key): array => [
                'key' => $key,
                ...$group,
                ...$this->groupStats($key),
            ])
            ->values()
            ->all();
    }

    /**
     * Clear the cached data for a group.
     *
     * @throws InvalidArgumentException When the group is unknown.
     */
    public function clear(string $group): void
    {
        switch ($group) {
            case 'settings':
                $this->clearSettingsCache();
                break;
            case 'seo':
                $this->clearSeoCache();
                break;
            case 'versions':
                Cache::forget(ApplicationVersionService::CACHE_KEY);
                break;
            case 'app':
                Cache::flush();
                $this->rebuildPermissionCache();
                break;
            case 'framework':
                $this->clearFrameworkCache();
                break;
            default:
                throw new InvalidArgumentException("Unknown cache group [{$group}].");
        }
    }

    /**
     * Warm or rebuild the cached data for a group.
     *
     * @throws InvalidArgumentException When the group is unknown or not optimizable.
     */
    public function optimize(string $group): void
    {
        switch ($group) {
            case 'settings':
                $this->settings->all();
                break;
            case 'seo':
                $this->rebuildSeoCache();
                break;
            case 'versions':
                $this->versions->current();
                break;
            case 'framework':
                $this->optimizeFrameworkCache();
                break;
            default:
                throw new InvalidArgumentException("Cache group [{$group}] cannot be optimized.");
        }
    }

    /**
     * Clear every cache group.
     */
    public function clearAll(): void
    {
        $this->clearSettingsCache();
        $this->clearSeoCache();
        Cache::forget(ApplicationVersionService::CACHE_KEY);
        Cache::flush();
        $this->clearFrameworkCache();
        $this->rebuildPermissionCache();
    }

    /**
     * Optimize every optimizable cache group.
     */
    public function optimizeAll(): void
    {
        $this->settings->all();
        $this->rebuildSeoCache();
        $this->versions->current();
        $this->optimizeFrameworkCache();
    }

    /**
     * Resolve entry count and byte size for one group.
     *
     * @return array{entries: int|null, size_bytes: int|null}
     */
    private function groupStats(string $group): array
    {
        if ($group === 'framework') {
            return $this->frameworkStats();
        }

        if (config('cache.default') !== 'database') {
            return ['entries' => null, 'size_bytes' => null];
        }

        return match ($group) {
            'settings' => $this->queryStats(fn ($query) => $query->where('key', 'like', '%company_setting.%')),
            'seo' => $this->queryStats(fn ($query) => $query->where('key', 'like', '%seo.sitemap%')->orWhere('key', 'like', '%seo.robots%')),
            'versions' => $this->queryStats(fn ($query) => $query->where('key', 'like', '%application-version.%')),
            'app' => $this->queryStats(fn ($query) => $query
                ->where('key', 'not like', '%company_setting.%')
                ->where('key', 'not like', '%seo.sitemap%')
                ->where('key', 'not like', '%seo.robots%')
                ->where('key', 'not like', '%application-version.%')
            ),
            default => ['entries' => null, 'size_bytes' => null],
        };
    }

    /**
     * Query the database cache store for entry count and size.
     *
     * @return array{entries: int, size_bytes: int}
     */
    private function queryStats(callable $filter): array
    {
        $query = DB::table('cache');
        $filter($query);

        $row = $query->selectRaw('COUNT(*) as entries, COALESCE(SUM(LENGTH(value)), 0) as size_bytes')->first();

        return [
            'entries' => (int) $row->entries,
            'size_bytes' => (int) $row->size_bytes,
        ];
    }

    /**
     * Count cached framework files and compiled views.
     *
     * @return array{entries: int, size_bytes: int}
     */
    private function frameworkStats(): array
    {
        $files = array_filter([
            app()->getCachedConfigPath(),
            app()->getCachedRoutesPath(),
            app()->getCachedEventsPath(),
        ], fn (string $path): bool => File::exists($path));

        $entries = count($files);
        $sizeBytes = array_sum(array_map(fn (string $path): int => (int) File::size($path), array_values($files)));

        $views = File::exists($dir = storage_path('framework/views')) ? File::glob($dir.'/*.php') : [];
        $entries += count($views);
        $sizeBytes += (int) array_sum(array_map(fn (string $path): int => (int) File::size($path), $views));

        return ['entries' => $entries, 'size_bytes' => $sizeBytes];
    }

    /**
     * Forget every company setting cache key.
     */
    private function clearSettingsCache(): void
    {
        $keys = collect(array_keys($this->settings->getDefaults()))
            ->merge(CompanySetting::query()->pluck('key'))
            ->unique();

        foreach ($keys as $key) {
            Cache::forget("company_setting.{$key}");
        }
    }

    /**
     * Forget the rendered sitemap and robots caches.
     */
    private function clearSeoCache(): void
    {
        Cache::forget('seo.sitemap');
        Cache::forget('seo.robots');
    }

    /**
     * Rebuild and re-cache the sitemap and robots.txt output.
     */
    private function rebuildSeoCache(): void
    {
        $this->clearSeoCache();

        Cache::put(
            'seo.sitemap',
            (new BuildSitemap(baseUrl: (string) config('app.url')))->execute(),
            (int) config('seo.cache.sitemap_duration', 86400)
        );

        Cache::put(
            'seo.robots',
            (new GenerateRobotsTxt(baseUrl: (string) config('app.url'), sitemapUrl: route('sitemap')))->execute(),
            (int) config('seo.cache.robots_duration', 3600)
        );
    }

    /**
     * Rebuild Spatie's permission cache, which rides on the same store and
     * is therefore wiped by Cache::flush().
     */
    private function rebuildPermissionCache(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    /**
     * Clear config, route, view, and event caches.
     */
    private function clearFrameworkCache(): void
    {
        Artisan::call('config:clear');
        Artisan::call('route:clear');
        Artisan::call('view:clear');
        Artisan::call('event:clear');
    }

    /**
     * Rebuild config, route, event, and view caches.
     */
    private function optimizeFrameworkCache(): void
    {
        Artisan::call('config:cache');
        Artisan::call('route:cache');
        Artisan::call('event:cache');
        Artisan::call('view:cache');
    }
}
