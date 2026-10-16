<?php

declare(strict_types=1);

namespace App\Support\SystemOverview\Probes;

use App\Models\UserSession;
use App\Support\SystemOverview\ProbeCost;
use App\Support\SystemOverview\ProbeItem;
use App\Support\SystemOverview\ProbeStatus;
use App\Support\SystemOverview\SystemProbe;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Number;

/**
 * Live authentication state: framework session rows and the durable sign-in ledger.
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
        $items = [];

        if (config('session.driver') === 'database' && Schema::hasTable('sessions')) {
            $rows = DB::table('sessions')->selectRaw('COUNT(*) as live, COUNT(DISTINCT user_id) as users')->first();

            $items[] = ProbeItem::text(
                'Live sessions',
                Number::forHumans((int) $rows->live),
                Number::forHumans((int) $rows->users).' distinct user(s)',
            );
        }

        if (Schema::hasTable('user_sessions')) {
            $stale = UserSession::query()->stale()->count();

            $items[] = ProbeItem::flagged(
                'Recorded sign-ins',
                Number::forHumans(UserSession::query()->active()->count()).' active',
                $stale > 0 ? ProbeStatus::Warn : ProbeStatus::Ok,
                $stale > 0
                    ? $stale.' row(s) still open past the lifetime; sessions:prune closes them nightly.'
                    : null,
            )->withRoute('admin.sessions.index');
        }

        return $items;
    }
}
