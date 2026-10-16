<?php

declare(strict_types=1);

namespace App\Support\SystemOverview\Probes;

use App\Support\SystemOverview\ProbeCost;
use App\Support\SystemOverview\ProbeItem;
use App\Support\SystemOverview\ProbeStatus;
use App\Support\SystemOverview\SystemProbe;
use Carbon\CarbonInterval;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Number;
use Yammi\JobsMonitor\Infrastructure\Facade\YammiJobs;

/**
 * Queue backlog, failures, and the scheduled work that should be draining it.
 *
 * Two aggregates and one in-memory schedule listing: no payloads are loaded.
 */
final class QueueProbe implements SystemProbe
{
    public function key(): string
    {
        return 'queue';
    }

    public function label(): string
    {
        return 'Queue & Scheduler';
    }

    public function description(): string
    {
        return 'Backlog, failures, and scheduled commands.';
    }

    public function icon(): string
    {
        return 'arrow-path';
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
        $driver = (string) config('queue.default');

        $items = [ProbeItem::text('Connection', $driver)];

        $items = array_merge($items, $this->backlog());

        $items[] = $this->throughput();
        $items[] = $this->workers();
        $items[] = $this->schedule();

        if (Route::has('jobs-monitor.dashboard')) {
            $items[] = ProbeItem::text('Job monitoring', 'Open jobs monitor')->withRoute('jobs-monitor.dashboard');
        }

        return $items;
    }

    /**
     * Jobs actually run and failed lately, from the monitoring package's own table.
     *
     * The package records every run, which is the only place a successful job
     * leaves a trace: the framework tables keep pending and failed work only.
     */
    private function throughput(): ProbeItem
    {
        if (! Schema::hasTable('jobs_monitor')) {
            return ProbeItem::text('Throughput (24h)', 'Not recorded', 'Enable jobs monitoring to keep run history.');
        }

        $counts = YammiJobs::statusCounts('24h');

        return ProbeItem::flagged(
            'Throughput (24h)',
            Number::forHumans($counts['processed']).' succeeded, '.Number::forHumans($counts['failed']).' failed',
            $counts['failed'] > 0 ? ProbeStatus::Fail : ProbeStatus::Ok,
            Number::forHumans($counts['processing']).' still running.',
        );
    }

    /**
     * Whether a worker is alive to drain the backlog at all.
     */
    private function workers(): ProbeItem
    {
        if (! Schema::hasTable('jobs_monitor_worker_heartbeats')) {
            return ProbeItem::text('Workers', 'Unknown', 'The monitoring package does not track heartbeats here.');
        }

        $alive = array_sum(YammiJobs::aliveWorkersByQueue(now()->subMinutes(2)->toDateTimeImmutable()));

        return ProbeItem::flagged(
            'Workers',
            $alive === 0 ? 'None reporting' : Number::forHumans($alive).' alive',
            $alive === 0 ? ProbeStatus::Warn : ProbeStatus::Ok,
            $alive === 0 ? 'Nothing will drain the backlog until a worker runs.' : null,
        );
    }

    /**
     * Pending and reserved job counts, plus the age of the oldest waiter.
     *
     * @return list<ProbeItem>
     */
    private function backlog(): array
    {
        if (! Schema::hasTable('jobs')) {
            return [ProbeItem::flagged('Backlog', 'No jobs table', ProbeStatus::Fail)];
        }

        $row = DB::table('jobs')
            ->selectRaw('COUNT(*) as pending, SUM(CASE WHEN reserved_at IS NOT NULL THEN 1 ELSE 0 END) as reserved, MIN(created_at) as oldest')
            ->first();

        $pending = (int) $row->pending;
        $reserved = (int) $row->reserved;

        $items = [
            ProbeItem::flagged(
                'Pending jobs',
                Number::forHumans($pending),
                $pending > 0 ? ProbeStatus::Warn : ProbeStatus::Ok,
                $reserved > 0 ? $reserved.' reserved for a worker.' : null,
            ),
        ];

        if ($pending > 0 && $row->oldest !== null) {
            $items[] = ProbeItem::text(
                'Oldest waiting',
                CarbonInterval::seconds(max(0, time() - (int) $row->oldest))->cascade()->forHumans(['parts' => 2]),
            );
        }

        $items[] = $this->failures();

        return $items;
    }

    /**
     * Failed job counts, total and for the last day.
     */
    private function failures(): ProbeItem
    {
        if (! Schema::hasTable('failed_jobs')) {
            return ProbeItem::flagged('Failures', 'No failed_jobs table', ProbeStatus::Fail);
        }

        $total = DB::table('failed_jobs')->count();
        $recent = DB::table('failed_jobs')->where('failed_at', '>=', now()->subDay())->count();

        return ProbeItem::flagged(
            'Failed jobs (retryable)',
            Number::forHumans($total).' queued for retry, '.$recent.' in 24h',
            $total > 0 ? ProbeStatus::Fail : ProbeStatus::Ok,
        );
    }

    /**
     * The registered schedule, straight from the running application.
     */
    private function schedule(): ProbeItem
    {
        $events = app(Schedule::class)->events();

        $summaries = array_map(
            static fn (object $event): string => (string) $event->getSummaryForDisplay(),
            array_slice($events, 0, 4),
        );

        return ProbeItem::text(
            'Scheduled commands',
            Number::forHumans(count($events)),
            $summaries === [] ? null : implode(' · ', $summaries),
        );
    }
}
