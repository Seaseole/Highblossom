<?php

declare(strict_types=1);

namespace App\Support\SystemOverview\Probes;

use App\Services\SessionManager;
use App\Support\SystemOverview\ProbeCost;
use App\Support\SystemOverview\ProbeItem;
use App\Support\SystemOverview\ProbeStatus;
use App\Support\SystemOverview\SystemProbe;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Number;

/**
 * Live authentication state: framework session rows and the durable sign-in ledger.
 *
 * Every number comes from one SessionManager measurement so the rows cannot
 * disagree about what counts as live. "Live" means seen inside the framework
 * session lifetime; anything older is reported as stale rather than counted,
 * because the database driver only garbage-collects expired rows on a lottery.
 */
final class SessionProbe implements SystemProbe
{
    public function key(): string
    {
        return 'sessions';
    }

    public function label(): string
    {
        return 'Sessions';
    }

    public function description(): string
    {
        return 'Live session rows and recorded sign-ins.';
    }

    public function icon(): string
    {
        return 'users';
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
        $counts = app(SessionManager::class)->counts();
        $items = [];

        if (config('session.driver') === 'database' && Schema::hasTable('sessions')) {
            $items[] = ProbeItem::flagged(
                'Live (valid)',
                Number::forHumans($counts['framework_valid']),
                ProbeStatus::Ok,
                'Seen within the last '.$counts['lifetime_minutes'].' minutes · '
                    .$counts['framework_authenticated'].' authenticated, '
                    .$counts['framework_guest'].' guest · '
                    .$counts['distinct_users'].' distinct user(s)'
            );

            $items[] = ProbeItem::flagged(
                'Stale',
                Number::forHumans($counts['framework_stale']),
                $counts['framework_stale'] > 0 ? ProbeStatus::Warn : ProbeStatus::Ok,
                $counts['framework_stale'] > 0
                    ? 'Expired rows the driver has not garbage-collected yet. Prune now, or the hourly sessions:prune will.'
                    : 'Nothing is waiting past the lifetime.'
            );

            if ($counts['idle_timeout_minutes'] > 0) {
                $items[] = ProbeItem::text(
                    'Idle timeout',
                    $counts['idle_timeout_minutes'].' min',
                    'Sign-ins idle for this long are closed early, inside the lifetime.'
                );
            }
        }

        if (Schema::hasTable('user_sessions')) {
            $items[] = $this->signIns($counts);
            $items[] = $this->lastPruned($counts);
        }

        return $items;
    }

    /**
     * The ledger row: a sign-in only counts as active while its session still exists.
     *
     * @param array<string, mixed> $counts
     */
    private function signIns(array $counts): ProbeItem
    {
        $stale = (int) $counts['sign_ins_stale'];

        $hint = $stale > 0
            ? $stale.' open sign-in(s) whose session has expired, been lost or gone idle'
                .((int) $counts['sign_ins_orphaned'] > 0 ? ', '.$counts['sign_ins_orphaned'].' of them orphaned' : '')
                .'; sessions:prune closes them hourly.'
            : 'Every open sign-in still has a valid session behind it.';

        return ProbeItem::flagged(
            'Recorded sign-ins',
            Number::forHumans((int) $counts['sign_ins_live']).' active',
            $stale > 0 ? ProbeStatus::Warn : ProbeStatus::Ok,
            $hint
        )->withRoute('admin.sessions.index');
    }

    /**
     * When cleanup last ran, and what it did.
     *
     * @param array<string, mixed> $counts
     */
    private function lastPruned(array $counts): ProbeItem
    {
        $run = $counts['last_run'];

        if (! is_array($run) || ! isset($run['at'])) {
            $waiting = (int) $counts['framework_stale'] + (int) $counts['sign_ins_stale'];

            return ProbeItem::flagged(
                'Last pruned',
                'Never',
                $waiting > 0 ? ProbeStatus::Warn : ProbeStatus::Info,
                'The hourly sessions:prune has not run on this host yet.'
            );
        }

        return ProbeItem::text(
            'Last pruned',
            Carbon::createFromTimestamp((int) $run['at'])->diffForHumans(),
            'Deleted '.(int) $run['deleted'].' expired row(s), closed '.(int) $run['closed'].' sign-in(s), dropped '
                .(int) $run['purged'].' past the retention window.'
        );
    }
}
