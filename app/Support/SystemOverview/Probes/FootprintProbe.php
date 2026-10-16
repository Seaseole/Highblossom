<?php

declare(strict_types=1);

namespace App\Support\SystemOverview\Probes;

use App\Models\GalleryImage;
use App\Models\MediaRegistry;
use App\Services\CacheManagerService;
use App\Services\Content\ContentBlockCleanupService;
use App\Support\SystemOverview\BoundedSizeScanner;
use App\Support\SystemOverview\ProbeCost;
use App\Support\SystemOverview\ProbeItem;
use App\Support\SystemOverview\ProbeStatus;
use App\Support\SystemOverview\SystemProbe;
use Illuminate\Support\Number;
use Illuminate\Support\Str;

/**
 * How much disk, media, and cache space the application is using.
 *
 * Heavy by design: directory trees are walked here, so this section is warmed off
 * the request path. Uploaded bytes come from the media registry, which records
 * each size at upload time, because a local walk cannot see a bucket-backed disk.
 */
final class FootprintProbe implements SystemProbe
{
    /**
     * Application code trees measured for the 'Application code' line.
     *
     * @var list<string>
     */
    private const CODE_DIRECTORIES = ['app', 'bootstrap/cache', 'config', 'database', 'lang', 'packages', 'resources', 'routes'];

    public function __construct(
        private readonly BoundedSizeScanner $scanner,
        private readonly CacheManagerService $cache,
        private readonly ContentBlockCleanupService $cleanup,
    ) {}

    public function key(): string
    {
        return 'footprint';
    }

    public function label(): string
    {
        return 'Storage & Cache';
    }

    public function description(): string
    {
        return 'Volume space, upload folders, registered media, and cache groups.';
    }

    public function icon(): string
    {
        return 'cloud-arrow-up';
    }

    public function cost(): ProbeCost
    {
        return ProbeCost::Heavy;
    }

    /**
     * @return list<ProbeItem>
     */
    public function run(): array
    {
        return array_merge(
            [
                $this->volume(),
                $this->code(),
                $this->directory('Uploads', storage_path('app/public')),
                $this->directory('Framework storage', storage_path('framework')),
                $this->directory('Logs', storage_path('logs')),
                $this->tempUploads(),
                $this->media(),
                $this->images(),
            ],
            $this->cacheGroups(),
        );
    }

    private function volume(): ProbeItem
    {
        $path = base_path();
        $total = disk_total_space($path);
        $free = disk_free_space($path);

        if ($total === false || $free === false || $total <= 0) {
            return ProbeItem::flagged('Disk volume', 'Unavailable', ProbeStatus::Warn, 'This host does not report volume size.');
        }

        $percent = round((($total - $free) / $total) * 100, 1);

        return ProbeItem::flagged(
            'Disk volume',
            Number::fileSize((int) ($total - $free), precision: 1).' of '.Number::fileSize((int) $total, precision: 1),
            match (true) {
                $percent >= 90 => ProbeStatus::Fail,
                $percent >= 75 => ProbeStatus::Warn,
                default => ProbeStatus::Ok,
            },
            $percent.'% used, '.Number::fileSize((int) $free, precision: 1).' free.',
        );
    }

    private function code(): ProbeItem
    {
        $files = 0;
        $bytes = 0;
        $capped = false;

        foreach (self::CODE_DIRECTORIES as $directory) {
            $scan = $this->scanner->scan(base_path($directory));

            $files += $scan['files'];
            $bytes += $scan['bytes'];
            $capped = $capped || $scan['truncated'];
        }

        return ProbeItem::text(
            'Application code',
            Number::fileSize($bytes, precision: 1),
            $this->count($files, 'file').($capped ? ', measurement capped' : ''),
        );
    }

    /**
     * @param string $label Line label.
     * @param string $path  Absolute directory to measure.
     */
    private function directory(string $label, string $path): ProbeItem
    {
        $scan = $this->scanner->scan($path);

        return ProbeItem::text(
            $label,
            Number::fileSize($scan['bytes'], precision: 1),
            $this->count($scan['files'], 'file').($scan['truncated'] ? ', measurement capped' : ''),
        );
    }

    private function tempUploads(): ProbeItem
    {
        $orphans = $this->cleanup->countOrphanedUploads();

        return ProbeItem::flagged(
            'Temp uploads',
            $orphans === 0 ? 'Nothing held' : number_format($orphans).' waiting',
            $orphans === 0 ? ProbeStatus::Ok : ProbeStatus::Warn,
            $orphans === 0
                ? null
                : 'Past retention and referenced by no post; the nightly command clears them.',
        );
    }

    private function media(): ProbeItem
    {
        $row = MediaRegistry::query()
            ->selectRaw('COUNT(*) as files, COALESCE(SUM(file_size), 0) as bytes')
            ->first();

        return ProbeItem::text(
            'Registered media',
            Number::fileSize((int) $row->bytes, precision: 1),
            number_format((int) $row->files).' files, sized at upload'
                .(config('filesystems.disks.public.driver') === 's3' ? ' on a bucket' : ''),
        );
    }

    /**
     * A count with the noun it counts, singular or plural.
     *
     * @param int    $count Number of items.
     * @param string $noun  Singular noun to count.
     */
    private function count(int $count, string $noun): string
    {
        return number_format($count).' '.Str::plural($noun, $count);
    }

    private function images(): ProbeItem
    {
        $published = GalleryImage::query()->inGallery()->count();
        $library = GalleryImage::query()->where('source', GalleryImage::SOURCE_LIBRARY)->count();

        return ProbeItem::text(
            'Image rows',
            number_format($published).' in gallery',
            number_format($library).' held in the media library.',
        );
    }

    /**
     * Per-group cache sizes, straight from the existing cache manager registry.
     *
     * @return list<ProbeItem>
     */
    private function cacheGroups(): array
    {
        return array_map(
            fn (array $group): ProbeItem => ProbeItem::text(
                'Cache · '.$group['label'],
                $group['size_bytes'] === null ? null : Number::fileSize((int) $group['size_bytes'], precision: 1),
                number_format((int) $group['entries']).' entries',
            )->withRoute('admin.cache.index'),
            $this->cache->stats(),
        );
    }
}
