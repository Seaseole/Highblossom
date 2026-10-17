<?php

declare(strict_types=1);

use App\Models\UserSession;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

$cutoff = now()->subMinutes((int) config('session.lifetime'))->getTimestamp();

if (! Schema::hasTable('sessions')) {
    echo "no sessions table\n";

    return;
}

$s = DB::table('sessions')->selectRaw(
    'COUNT(*) as total,
     SUM(CASE WHEN last_activity > ? THEN 1 ELSE 0 END) as valid,
     SUM(CASE WHEN last_activity <= ? THEN 1 ELSE 0 END) as expired,
     SUM(CASE WHEN user_id IS NULL THEN 1 ELSE 0 END) as guest,
     SUM(CASE WHEN user_id IS NOT NULL THEN 1 ELSE 0 END) as authed,
     COUNT(DISTINCT user_id) as distinct_incl_null,
     COUNT(DISTINCT CASE WHEN user_id IS NOT NULL THEN user_id END) as distinct_authed,
     MIN(last_activity) as oldest_ms,
     MAX(last_activity) as newest_ms',
    [$cutoff, $cutoff]
)->first();

echo "sessions table (lifetime ".config('session.lifetime')." min):\n";
echo "  total={$s->total} valid={$s->valid} expired={$s->expired} guest={$s->guest} authenticated={$s->authed}\n";
echo "  COUNT(DISTINCT user_id) [what the card prints] = {$s->distinct_incl_null}\n";
echo "  distinct real users                            = {$s->distinct_authed}\n";
if ($s->oldest_ms) {
    echo '  oldest row '.now()->createFromTimestamp((int) $s->oldest_ms)->diffForHumans()
        .", newest ".now()->createFromTimestamp((int) $s->newest_ms)->diffForHumans()."\n";
}

if (! Schema::hasTable('user_sessions')) {
    echo "no user_sessions table\n";

    return;
}

$open = DB::table('user_sessions')->whereNull('ended_at')->count();
$active = UserSession::query()->active()->count();
$stale = UserSession::query()->stale()->count();
$openAuthed = DB::table('user_sessions')->whereNull('ended_at')->whereNotNull('user_id')->count();

echo "\nuser_sessions ledger:\n";
echo "  total=".DB::table('user_sessions')->count()." open={$open} (authed {$openAuthed}) closed=".(DB::table('user_sessions')->whereNotNull('ended_at')->count())."\n";
echo "  active() scope={$active}  stale() scope={$stale}\n";

$orphanLedger = UserSession::query()
    ->whereNull('ended_at')
    ->whereNotNull('session_id')
    ->whereNotExists(fn ($q) => $q->select(DB::raw(1))->from('sessions')->whereColumn('sessions.id', 'user_sessions.session_id'))
    ->count();

$ledgerSessionIds = DB::table('user_sessions')->whereNotNull('session_id')->pluck('session_id')->all();
$sessionRowsWithoutLedger = DB::table('sessions')
    ->when($ledgerSessionIds !== [], fn ($q) => $q->whereNotIn('id', $ledgerSessionIds))
    ->count();

echo "  orphaned ledger rows (open, session row gone) = {$orphanLedger}\n";
echo "  sessions rows with no ledger row              = {$sessionRowsWithoutLedger}\n";

$reasons = DB::table('user_sessions')->whereNotNull('ended_at')
    ->selectRaw('end_reason, COUNT(*) as c')->groupBy('end_reason')->pluck('c', 'end_reason')->all();
echo '  end reasons: '.json_encode($reasons)."\n";

echo "\nsessions rows grouped by user_id:\n";
foreach (DB::table('sessions')
    ->selectRaw('user_id, COUNT(*) as rows_total, SUM(CASE WHEN last_activity > ? THEN 1 ELSE 0 END) as rows_valid', [$cutoff])
    ->groupBy('user_id')->get() as $row) {
    echo '  user_id='.var_export($row->user_id, true)." total={$row->rows_total} valid={$row->rows_valid}\n";
}
