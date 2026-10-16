<?php

declare(strict_types=1);

namespace App\Support\SystemOverview;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use JsonException;

/**
 * The declared dependency set, parsed from composer.lock and package.json.
 *
 * The lock file is read once per fingerprint rather than once per view: the totals and
 * the row list are cached under fixed keys with the fingerprint stored alongside them,
 * so editing a dependency invalidates the cache by comparison instead of by TTL. Caching
 * the rows is what lets the package table answer a search keystroke by filtering an
 * array in memory, rather than decoding another megabyte of JSON.
 */
final class PackageCatalog
{
    /**
     * The source filters the package table may ask for.
     *
     * @var list<string>
     */
    public const SOURCES = ['all', 'composer', 'npm'];

    private const COUNTS_KEY = 'system_overview.packages.counts';

    private const ROWS_KEY = 'system_overview.packages.rows';

    private const CACHE_TTL_SECONDS = 3600;

    /**
     * @var array<string, array<string, mixed>>
     */
    private array $documents = [];

    /**
     * Package totals, small enough to render on a page.
     *
     * @return array{composer: int, composer_dev: int, npm: int, npm_dev: int, locked_at: string|null}
     */
    public function counts(): array
    {
        $fingerprint = $this->fingerprint();
        $cached = Cache::get(self::COUNTS_KEY);

        if (is_array($cached)
            && ($cached['fingerprint'] ?? null) === $fingerprint
            && is_array($cached['counts'] ?? null)) {
            /** @var array{composer: int, composer_dev: int, npm: int, npm_dev: int, locked_at: string|null} $counts */
            $counts = $cached['counts'];

            return $counts;
        }

        $counts = $this->measureCounts();

        Cache::put(
            self::COUNTS_KEY,
            ['fingerprint' => $fingerprint, 'counts' => $counts],
            self::CACHE_TTL_SECONDS,
        );

        return $counts;
    }

    /**
     * Every Composer package, production and development, sorted by name.
     *
     * @return list<array{name: string, version: string, type: string, description: string, homepage: string|null, dev: bool}>
     */
    public function composerPackages(): array
    {
        $lock = $this->lock();
        $rows = [];

        foreach (['packages' => false, 'packages-dev' => true] as $section => $dev) {
            foreach ($lock[$section] ?? [] as $package) {
                $rows[] = [
                    'name' => (string) ($package['name'] ?? ''),
                    'version' => (string) ($package['version'] ?? ''),
                    'type' => (string) ($package['type'] ?? 'library'),
                    'description' => (string) ($package['description'] ?? ''),
                    'homepage' => $this->homepageOf($package),
                    'dev' => $dev,
                ];
            }
        }

        usort($rows, static fn (array $a, array $b): int => strcasecmp($a['name'], $b['name']));

        return $rows;
    }

    /**
     * Every JavaScript dependency named in package.json.
     *
     * @return list<array{name: string, version: string, type: string, description: string, homepage: string|null, dev: bool}>
     */
    public function npmPackages(): array
    {
        $manifest = $this->manifest();
        $rows = [];

        foreach (['dependencies' => false, 'devDependencies' => true] as $section => $dev) {
            foreach ($manifest[$section] ?? [] as $name => $version) {
                $rows[] = [
                    'name' => (string) $name,
                    'version' => (string) $version,
                    'type' => 'npm',
                    'description' => '',
                    'homepage' => null,
                    'dev' => $dev,
                ];
            }
        }

        usort($rows, static fn (array $a, array $b): int => strcasecmp($a['name'], $b['name']));

        return $rows;
    }

    /**
     * Every declared package from both lock files, sorted by name.
     *
     * @return list<array{name: string, version: string, type: string, description: string, homepage: string|null, dev: bool, source: string}>
     */
    public function rows(): array
    {
        $fingerprint = $this->fingerprint();
        $cached = Cache::get(self::ROWS_KEY);

        if (is_array($cached)
            && ($cached['fingerprint'] ?? null) === $fingerprint
            && is_array($cached['rows'] ?? null)) {
            /** @var list<array{name: string, version: string, type: string, description: string, homepage: string|null, dev: bool, source: string}> $rows */
            $rows = $cached['rows'];

            return $rows;
        }

        $rows = $this->measureRows();

        Cache::put(
            self::ROWS_KEY,
            ['fingerprint' => $fingerprint, 'rows' => $rows],
            self::CACHE_TTL_SECONDS,
        );

        return $rows;
    }

    /**
     * One page of the package list, narrowed by search term and source.
     */
    public function paginate(string $search = '', string $source = 'all', int $page = 1, int $perPage = 15): LengthAwarePaginator
    {
        $rows = $this->filter($search, $source);
        $page = max($page, 1);

        return new LengthAwarePaginator(
            array_slice($rows, ($page - 1) * $perPage, $perPage),
            count($rows),
            $perPage,
            $page,
        );
    }

    /**
     * Drop the cached totals and rows, for after a dependency change.
     */
    public function forget(): void
    {
        Cache::forget(self::COUNTS_KEY);
        Cache::forget(self::ROWS_KEY);
    }

    /**
     * @return list<array{name: string, version: string, type: string, description: string, homepage: string|null, dev: bool, source: string}>
     */
    private function measureRows(): array
    {
        $rows = [];

        foreach (['composer' => $this->composerPackages(), 'npm' => $this->npmPackages()] as $source => $set) {
            foreach ($set as $package) {
                $rows[] = [...$package, 'source' => $source];
            }
        }

        usort($rows, static fn (array $a, array $b): int => strcasecmp($a['name'], $b['name']));

        return $rows;
    }

    /**
     * Search matches a name or a description, case-insensitively. An unknown source is
     * treated as no filter, so the allowlist stays with the caller.
     *
     * @return list<array<string, mixed>>
     */
    private function filter(string $search, string $source): array
    {
        $needle = strtolower(trim($search));

        $rows = $this->rows();

        if ($source === 'composer' || $source === 'npm') {
            $rows = array_filter($rows, static fn (array $package): bool => $package['source'] === $source);
        }

        if ($needle === '') {
            return array_values($rows);
        }

        return array_values(array_filter(
            $rows,
            static fn (array $package): bool => str_contains(strtolower($package['name']), $needle)
                || str_contains(strtolower($package['description']), $needle)
        ));
    }

    /**
     * @return array{composer: int, composer_dev: int, npm: int, npm_dev: int, locked_at: string|null}
     */
    private function measureCounts(): array
    {
        $lock = $this->lock();
        $manifest = $this->manifest();
        $path = base_path('composer.lock');

        return [
            'composer' => count($lock['packages'] ?? []),
            'composer_dev' => count($lock['packages-dev'] ?? []),
            'npm' => count($manifest['dependencies'] ?? []),
            'npm_dev' => count($manifest['devDependencies'] ?? []),
            'locked_at' => File::exists($path) ? date('Y-m-d H:i', (int) filemtime($path)) : null,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function lock(): array
    {
        return $this->read('composer.lock');
    }

    /**
     * composer.lock stores homepage as a string, or as a list when a package
     * declares more than one. The table renders the value as a link, so anything
     * that is not http(s) is dropped rather than passed on as a clickable scheme.
     *
     * @param array{name?: string, version?: string, type?: string, description?: string, homepage?: string|list<string>} $package
     */
    private function homepageOf(array $package): ?string
    {
        $homepage = $package['homepage'] ?? null;

        if (is_array($homepage)) {
            $homepage = $homepage[0] ?? null;
        }

        if (! is_string($homepage)) {
            return null;
        }

        $homepage = trim($homepage);

        if (preg_match('#^https?://#i', $homepage) !== 1) {
            return null;
        }

        return $homepage;
    }

    /**
     * @return array<string, mixed>
     */
    private function manifest(): array
    {
        return $this->read('package.json');
    }

    /**
     * @param string $file File name relative to the project root.
     *
     * @return array<string, mixed>
     */
    private function read(string $file): array
    {
        $path = base_path($file);

        if (array_key_exists($path, $this->documents)) {
            return $this->documents[$path];
        }

        if (! File::exists($path)) {
            return $this->documents[$path] = [];
        }

        try {
            /** @var array<string, mixed> $decoded */
            $decoded = json_decode(File::get($path), true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException) {
            return $this->documents[$path] = [];
        }

        return $this->documents[$path] = $decoded;
    }

    /**
     * mtime and size of both manifests, so a dependency edit changes the key.
     */
    private function fingerprint(): string
    {
        $parts = [];

        foreach (['composer.lock', 'package.json'] as $file) {
            $path = base_path($file);

            $parts[] = File::exists($path)
                ? filemtime($path).':'.filesize($path)
                : 'missing';
        }

        return implode('|', $parts);
    }
}
