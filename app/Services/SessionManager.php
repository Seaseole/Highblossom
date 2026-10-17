<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\SessionEndReason;
use App\Models\UserSession;
use App\Services\Settings\SettingsManager;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

/**
 * Owns session reconciliation: measuring what is genuinely live, deleting the
 * framework session rows the driver only garbage-collects probabilistically, and
 * closing the ledger rows that describe them.
 *
 * Separate from UserSessionService, which owns the lifecycle of one sign-in
 * (open, touch, revoke). This class owns the sweep across all of them, so the
 * scheduled command, the manual admin action and the overview card cannot each
 * carry their own idea of what a live session is.
 */
final class SessionManager
{
    /**
     * Cache key holding the summary of the most recent prune.
     */
    public const LAST_RUN_KEY = 'session-manager:last-prune';

    /**
     * Rows touched per statement, so a large backlog cannot hold one long lock.
     */
    private const CHUNK = 500;

    public function __construct(
        private readonly SettingsManager $settings,
    ) {}

    /**
     * Measure both session stores for the overview card.
     *
     * Every number the card shows comes from here, so the rows cannot disagree
     * with each other about what "live" means.
     *
     * @return array{
     *     framework_total: int,
     *     framework_valid: int,
     *     framework_authenticated: int,
     *     framework_guest: int,
     *     framework_stale: int,
     *     distinct_users: int,
     *     sign_ins_open: int,
     *     sign_ins_live: int,
     *     sign_ins_stale: int,
     *     sign_ins_orphaned: int,
     *     idle_timeout_minutes: int,
     *     lifetime_minutes: int,
     *     last_run: array<string, mixed>|null
     * }
     */
    public function counts(): array
    {
        $cutoff = UserSession::frameworkCutoff();
        $framework = [
            'framework_total' => 0,
            'framework_valid' => 0,
            'framework_authenticated' => 0,
            'framework_guest' => 0,
            'framework_stale' => 0,
            'distinct_users' => 0,
        ];

        if (($table = $this->sessionTable()) !== null) {
            $row = DB::table($table)->selectRaw(
                'COUNT(*) as total,
                 SUM(CASE WHEN last_activity > ? THEN 1 ELSE 0 END) as valid,
                 SUM(CASE WHEN last_activity > ? AND user_id IS NOT NULL THEN 1 ELSE 0 END) as authenticated,
                 SUM(CASE WHEN last_activity > ? AND user_id IS NULL THEN 1 ELSE 0 END) as guest,
                 SUM(CASE WHEN last_activity <= ? THEN 1 ELSE 0 END) as stale,
                 COUNT(DISTINCT CASE WHEN last_activity > ? THEN user_id END) as users',
                [$cutoff, $cutoff, $cutoff, $cutoff, $cutoff]
            )->first();

            $framework = [
                'framework_total' => (int) $row->total,
                'framework_valid' => (int) $row->valid,
                'framework_authenticated' => (int) $row->authenticated,
                'framework_guest' => (int) $row->guest,
                'framework_stale' => (int) $row->stale,
                'distinct_users' => (int) $row->users,
            ];
        }

        $open = UserSession::query()->whereNull('ended_at')->count();
        $live = UserSession::query()->live()->count();

        return $framework + [
            'sign_ins_open' => $open,
            'sign_ins_live' => $live,
            'sign_ins_stale' => max(0, $open - $live),
            'sign_ins_orphaned' => UserSession::query()->orphaned()->count(),
            'idle_timeout_minutes' => $this->idleTimeoutMinutes(),
            'lifetime_minutes' => UserSession::lifetimeMinutes(),
            'last_run' => $this->lastRun(),
        ];
    }

    /**
     * Reconcile both stores and report what changed.
     *
     * Each batch is its own transaction rather than one spanning the sweep: a
     * backlog of thousands of expired rows should not hold a single long lock, and
     * every step is driven by state (`ended_at IS NULL`, `last_activity <= cutoff`)
     * so a run that stops halfway is simply finished by the next one.
     *
     * @param int|null    $retentionDays   How long closed ledger rows are kept; defaults to config.
     * @param bool        $dryRun          Report what would change without writing anything.
     * @param string|null $exceptSessionId Session id that must survive, i.e. the administrator's own.
     *
     * @return array{deleted: int, closed: int, expired: int, orphaned: int, idle: int, purged: int, dry_run: bool}
     */
    public function prune(?int $retentionDays = null, bool $dryRun = false, ?string $exceptSessionId = null): array
    {
        $retentionDays ??= (int) config('user-sessions.history_retention_days');
        $table = $this->sessionTable();

        $summary = [
            'deleted' => 0,
            'closed' => 0,
            'expired' => 0,
            'orphaned' => 0,
            'idle' => 0,
            'purged' => 0,
            'dry_run' => $dryRun,
        ];

        // Idle timeout is a real sign-out, so the framework row goes with the ledger
        // row; closing only the ledger would leave the browser signed in.
        $idleMinutes = $this->idleTimeoutMinutes();

        if ($idleMinutes > 0) {
            $idle = $this->openLedger($exceptSessionId)
                ->whereNotNull('session_id')
                ->where('last_seen_at', '<=', now()->subMinutes($idleMinutes));

            $summary['idle'] = $dryRun
                ? $idle->count()
                : $this->closeInChunks($idle, SessionEndReason::IDLE_TIMEOUT, $table);
        }

        $summary['deleted'] = $this->deleteExpiredFrameworkRows($table, $dryRun, $exceptSessionId);

        $stale = $this->openLedger($exceptSessionId)->stale();
        $summary['expired'] = $dryRun
            ? $stale->count()
            : $this->closeInChunks($stale, SessionEndReason::EXPIRED);

        // After the expired framework rows are gone, an open ledger row with no row
        // left to point at is orphaned rather than merely old.
        $orphaned = $this->openLedger($exceptSessionId)->orphaned();
        $summary['orphaned'] = $dryRun
            ? $orphaned->count()
            : $this->closeInChunks($orphaned, SessionEndReason::ORPHANED);

        $summary['purged'] = $this->purgeHistory($retentionDays, $dryRun);
        $summary['closed'] = $summary['expired'] + $summary['orphaned'] + $summary['idle'];

        if (! $dryRun) {
            $this->recordRun($summary);
        }

        return $summary;
    }

    /**
     * The summary of the most recent prune, or null when it has never run.
     *
     * @return array<string, mixed>|null
     */
    public function lastRun(): ?array
    {
        $record = Cache::get(self::LAST_RUN_KEY);

        return is_array($record) ? $record : null;
    }

    /**
     * Minutes of inactivity after which a session is closed early, or 0 when the
     * administrator has left the framework lifetime in charge.
     */
    public function idleTimeoutMinutes(): int
    {
        return max(0, (int) $this->settings->get('session_idle_timeout_minutes', 0));
    }

    /**
     * The framework session table, or null when sessions are not stored in the database.
     */
    private function sessionTable(): ?string
    {
        if (config('session.driver') !== 'database') {
            return null;
        }

        $table = (string) config('session.table', 'sessions');

        return Schema::hasTable($table) ? $table : null;
    }

    /**
     * Base query for ledger rows that are still open, minus one protected session.
     *
     * @return Builder<UserSession>
     */
    private function openLedger(?string $exceptSessionId): Builder
    {
        return UserSession::query()
            ->whereNull('ended_at')
            ->when($exceptSessionId, fn (Builder $query) => $query->where('session_id', '!=', $exceptSessionId));
    }

    /**
     * Delete framework session rows past the lifetime, in id batches.
     */
    private function deleteExpiredFrameworkRows(?string $table, bool $dryRun, ?string $exceptSessionId): int
    {
        if ($table === null) {
            return 0;
        }

        $cutoff = UserSession::frameworkCutoff();

        $expired = fn () => DB::table($table)
            ->where('last_activity', '<=', $cutoff)
            ->when($exceptSessionId, fn ($query) => $query->where('id', '!=', $exceptSessionId));

        if ($dryRun) {
            return $expired()->count();
        }

        $deleted = 0;

        while (true) {
            $ids = $expired()->limit(self::CHUNK)->pluck('id');

            if ($ids->isEmpty()) {
                break;
            }

            $deleted += DB::transaction(static fn (): int => DB::table($table)->whereIn('id', $ids)->delete());

            if ($ids->count() < self::CHUNK) {
                break;
            }
        }

        return $deleted;
    }

    /**
     * Close ledger rows in batches, dropping their framework rows so the browser is
     * signed out rather than only losing its history entry.
     *
     * @param Builder<UserSession> $ledger
     */
    private function closeInChunks(Builder $ledger, SessionEndReason $reason, ?string $table = null): int
    {
        $closed = 0;

        while (true) {
            $batch = (clone $ledger)->orderBy('id')->limit(self::CHUNK)->get(['id', 'session_id']);

            if ($batch->isEmpty()) {
                break;
            }

            $closed += DB::transaction(function () use ($batch, $reason, $table): int {
                if ($table !== null) {
                    $nativeIds = $batch->pluck('session_id')->filter()->values();

                    if ($nativeIds->isNotEmpty()) {
                        DB::table($table)->whereIn('id', $nativeIds)->delete();
                    }
                }

                // Ended as of the last moment the session was seen, which is the most
                // truthful timestamp available for a row nobody signed out of.
                return UserSession::whereIn('id', $batch->pluck('id'))
                    ->whereNull('ended_at')
                    ->update([
                        'ended_at' => DB::raw('last_seen_at'),
                        'end_reason' => $reason->value,
                    ]);
            });

            if ($batch->count() < self::CHUNK) {
                break;
            }
        }

        return $closed;
    }

    /**
     * Drop closed ledger rows past the retention window, in id batches.
     */
    private function purgeHistory(int $retentionDays, bool $dryRun): int
    {
        $before = now()->subDays($retentionDays);

        $history = fn () => UserSession::query()
            ->whereNotNull('ended_at')
            ->where('ended_at', '<', $before);

        if ($dryRun) {
            return $history()->count();
        }

        $purged = 0;

        while (true) {
            $ids = $history()->orderBy('id')->limit(self::CHUNK)->pluck('id');

            if ($ids->isEmpty()) {
                break;
            }

            $purged += DB::transaction(static fn (): int => UserSession::whereIn('id', $ids)->delete());

            if ($ids->count() < self::CHUNK) {
                break;
            }
        }

        return $purged;
    }

    /**
     * Store the run so the card can say when cleanup last happened, and log the
     * counts: on an ephemeral host the log line is the only durable trace.
     *
     * @param array<string, int|bool> $summary
     */
    private function recordRun(array $summary): void
    {
        $record = $summary;
        unset($record['dry_run']);
        $record['at'] = now()->getTimestamp();

        Cache::put(self::LAST_RUN_KEY, $record);

        Log::info('Session prune completed.', $record);
    }
}
