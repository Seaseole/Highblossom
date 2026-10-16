<?php

declare(strict_types=1);

namespace App\Support\SystemOverview\Probes;

use App\Support\SystemOverview\ProbeCost;
use App\Support\SystemOverview\ProbeItem;
use App\Support\SystemOverview\ProbeStatus;
use App\Support\SystemOverview\SystemProbe;
use Carbon\CarbonInterval;
use Illuminate\Support\Facades\File;

/**
 * Which backing stores the application is wired to, read from configuration only.
 *
 * Sizes and entry counts deliberately live on the Cache page; this section
 * answers "what would break", not "how big is it", so it costs no queries.
 */
final class StoresProbe implements SystemProbe
{
    public function key(): string
    {
        return 'stores';
    }

    public function label(): string
    {
        return 'Backing Stores';
    }

    public function description(): string
    {
        return 'Cache, session, queue, mail, and filesystem drivers.';
    }

    public function icon(): string
    {
        return 'globe-alt';
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
        $sessionDriver = (string) config('session.driver');
        $lifetime = (int) config('session.lifetime');

        return [
            ProbeItem::text('Cache store', (string) config('cache.default'), 'Prefix: '.(string) config('cache.prefix')),
            ProbeItem::text('Session store', $sessionDriver, 'Expires after '.$this->forHumans($lifetime))
                ->withRoute('admin.sessions.index'),
            ProbeItem::flagged(
                'Session cookies',
                $this->cookieSummary(),
                $this->cookieStatus(),
                config('session.secure') ? null : 'Cookies are not flagged Secure.',
            ),
            ProbeItem::text('Queue connection', (string) config('queue.default')),
            ProbeItem::text('Mail sender', (string) config('mail.default'), (string) config('mail.from.address')),
            ProbeItem::text('Default disk', (string) config('filesystems.default')),
            ProbeItem::text('Public disk', (string) config('filesystems.disks.public.driver')),
            ProbeItem::flagged(
                'Storage link',
                $this->linkState(),
                $this->linkStatus(),
                $this->linkHint(),
            ),
            ProbeItem::text('Cache groups', 'Sizes and controls', 'Inspect and clear on the Cache page.')
                ->withRoute('admin.cache.index'),
        ];
    }

    /**
     * @param int $minutes Session lifetime in minutes.
     */
    private function forHumans(int $minutes): string
    {
        if ($minutes <= 0) {
            return 'never';
        }

        return CarbonInterval::minutes($minutes)->cascade()->forHumans(['parts' => 2]);
    }

    private function cookieSummary(): string
    {
        $flags = [];

        $flags[] = config('session.http_only') ? 'HttpOnly' : 'no HttpOnly';
        $flags[] = config('session.secure') ? 'Secure' : 'not Secure';
        $flags[] = 'SameSite '.ucfirst((string) config('session.same_site', 'lax'));

        return implode(' · ', $flags);
    }

    /**
     * Cookies are only a real finding when the app serves production traffic.
     */
    private function cookieStatus(): ProbeStatus
    {
        if (! config('session.http_only')) {
            return ProbeStatus::Warn;
        }

        if (config('app.env') === 'production' && ! config('session.secure')) {
            return ProbeStatus::Warn;
        }

        return config('session.secure') ? ProbeStatus::Ok : ProbeStatus::Info;
    }

    /**
     * The link only matters while the public disk is served from this host.
     */
    private function usesLocalPublicDisk(): bool
    {
        return config('filesystems.disks.public.driver') !== 's3';
    }

    private function linkState(): string
    {
        if (! $this->usesLocalPublicDisk()) {
            return 'Not needed';
        }

        return File::exists(public_path('storage')) ? 'Linked' : 'Missing';
    }

    private function linkStatus(): ProbeStatus
    {
        if (! $this->usesLocalPublicDisk()) {
            return ProbeStatus::Info;
        }

        return File::exists(public_path('storage')) ? ProbeStatus::Ok : ProbeStatus::Fail;
    }

    private function linkHint(): ?string
    {
        if (! $this->usesLocalPublicDisk()) {
            return 'The public disk is served from object storage.';
        }

        return File::exists(public_path('storage'))
            ? 'public/storage resolves to the local disk.'
            : 'Run php artisan storage:link.';
    }
}
