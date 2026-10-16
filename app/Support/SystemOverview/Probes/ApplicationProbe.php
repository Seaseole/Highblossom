<?php

declare(strict_types=1);

namespace App\Support\SystemOverview\Probes;

use App\Services\ApplicationVersionService;
use App\Support\SystemOverview\ProbeCost;
use App\Support\SystemOverview\ProbeItem;
use App\Support\SystemOverview\ProbeStatus;
use App\Support\SystemOverview\SystemProbe;
use Illuminate\Contracts\Foundation\Application;

/**
 * Framework and application state, including the release registry.
 */
final class ApplicationProbe implements SystemProbe
{
    public function __construct(
        private readonly Application $app,
        private readonly ApplicationVersionService $versions,
    ) {}

    public function key(): string
    {
        return 'application';
    }

    public function label(): string
    {
        return 'Application';
    }

    public function description(): string
    {
        return 'Framework, configuration, and the recorded application release.';
    }

    public function icon(): string
    {
        return 'power';
    }

    public function cost(): ProbeCost
    {
        return ProbeCost::Light;
    }

    /**
     * @return list<ProbeItem>
     */
    public function run(): array
    {
        $environment = (string) config('app.env');
        $isProduction = $environment === 'production';
        $release = $this->versions->current();

        return [
            ProbeItem::text('Framework', 'Laravel '.$this->app->version()),
            ProbeItem::text('Name', (string) config('app.name')),
            ProbeItem::text('Environment', ucfirst($environment)),
            ProbeItem::flagged(
                'Debug mode',
                config('app.debug') ? 'On' : 'Off',
                config('app.debug') ? ProbeStatus::Warn : ProbeStatus::Ok,
                'Errors would expose stack traces while this is on.',
            ),
            ProbeItem::text('URL', (string) config('app.url')),
            ProbeItem::text('Timezone', (string) config('app.timezone')),
            ProbeItem::text('Locale', (string) config('app.locale')),
            ProbeItem::flagged(
                'Maintenance mode',
                $this->app->isDownForMaintenance() ? 'Active' : 'Off',
                $this->app->isDownForMaintenance() ? ProbeStatus::Warn : ProbeStatus::Ok,
            ),
            ProbeItem::flagged(
                'Config cache',
                $this->app->configurationIsCached() ? 'Cached' : 'Not cached',
                $this->cachedStatus($this->app->configurationIsCached(), $isProduction),
            ),
            ProbeItem::flagged(
                'Route cache',
                $this->app->routesAreCached() ? 'Cached' : 'Not cached',
                $this->cachedStatus($this->app->routesAreCached(), $isProduction),
            ),
            ProbeItem::flagged(
                'Event cache',
                $this->app->eventsAreCached() ? 'Cached' : 'Not cached',
                $this->cachedStatus($this->app->eventsAreCached(), $isProduction),
            ),
            ProbeItem::text(
                'Release',
                $release === null ? ApplicationVersionService::BASELINE : $release->version,
                $release === null
                    ? 'No release recorded yet.'
                    : 'Recorded '.$release->created_at?->toFormattedDateString(),
            )->withRoute('admin.versions.index'),
        ];
    }

    /**
     * A missing cache layer only warns in production; locally it is expected.
     *
     * @param bool $cached       Whether the artifact is cached.
     * @param bool $isProduction Whether the app runs in production.
     */
    private function cachedStatus(bool $cached, bool $isProduction): ProbeStatus
    {
        if ($cached) {
            return ProbeStatus::Ok;
        }

        return $isProduction ? ProbeStatus::Warn : ProbeStatus::Info;
    }
}
