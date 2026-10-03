<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\LoginMethod;
use App\Enums\SessionEndReason;
use App\Models\User;
use App\Models\UserSession;
use App\Support\DeviceInformation;
use Illuminate\Container\Attributes\Singleton;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Owns the `user_sessions` ledger: opening rows on sign-in, keeping them fresh,
 * closing them on sign-out or revocation, and pruning history.
 *
 * Revocation works by deleting the framework session row, which makes the
 * session unreadable on its next request. `Auth::logoutOtherDevices()` is not
 * used because it relies on the `AuthenticateSession` middleware, which this
 * application does not register.
 */
#[Singleton(name: 'user_sessions')]
final class UserSessionService
{
    /**
     * Record or refresh the ledger row for the session being served.
     *
     * Called after the request pipeline unwinds so the session id is the one the
     * framework settled on: Fortify regenerates the id after firing the Login event.
     *
     * `$viaRemember` covers the re-authentication path, where the session cookie
     * has expired or been cleared and the user is signed back in from their
     * remember token. Fortify fires no Login event there, so no handoff metadata
     * exists and the method has to be read off the guard instead.
     */
    public function track(User $user, Request $request, bool $viaRemember = false): void
    {
        $sessionId = $request->session()->getId();

        if ($sessionId === '') {
            return;
        }

        $existing = UserSession::where('session_id', $sessionId)->first();

        if ($existing !== null) {
            $this->touch($existing, $request);

            return;
        }

        $this->open($user, $request, $sessionId, $viaRemember);
    }

    /**
     * Open a new ledger row for a session that has never been recorded.
     */
    private function open(User $user, Request $request, string $sessionId, bool $viaRemember): void
    {
        $handoff = $request->session()->get($this->handoffKey(), []);
        $ip = $request->ip();
        $device = DeviceInformation::fromUserAgent($request->userAgent());
        $loginMethod = LoginMethod::tryFrom($handoff['login_method'] ?? '')
            ?? ($viaRemember ? LoginMethod::REMEMBER : LoginMethod::UNKNOWN);

        $attributes = [
            'user_id' => $user->getKey(),
            'session_id' => $sessionId,
            'ip_address' => $ip,
            'last_ip_address' => $ip,
            'user_agent' => $request->userAgent(),
            'login_method' => $loginMethod,
            'remembered' => $viaRemember || (bool) ($handoff['remembered'] ?? false),
            'login_at' => now(),
            'last_seen_at' => now(),
        ] + $device->toColumnArray();

        try {
            UserSession::create($attributes);
        } catch (UniqueConstraintViolationException) {
            // Two parallel requests on a brand-new session can both miss the
            // lookup; the loser falls through to refreshing the winner's row.
            $concurrent = UserSession::where('session_id', $sessionId)->first();

            if ($concurrent !== null) {
                $this->touch($concurrent, $request);
            }
        }

        $request->session()->forget($this->handoffKey());
    }

    /**
     * Refresh activity on a live ledger row, throttled to avoid a write per request.
     */
    private function touch(UserSession $session, Request $request): void
    {
        if (! $session->isActive()) {
            return;
        }

        $ip = (string) $request->ip();
        $throttledUntil = $session->last_seen_at->addSeconds((int) config('user-sessions.touch_throttle_seconds'));
        $ipChanged = $session->last_ip_address !== $ip;

        if (! $ipChanged && now()->lessThan($throttledUntil)) {
            return;
        }

        $session->forceFill([
            'last_seen_at' => now(),
            'last_ip_address' => $ip,
        ])->save();
    }

    /**
     * List the live sessions for a user, most recently seen first.
     *
     * @return Collection<int, UserSession>
     */
    public function activeFor(User $user): Collection
    {
        return $user->sessions()->active()->latest('last_seen_at')->get();
    }

    /**
     * List a user's closed sessions, most recently closed first.
     *
     * @return Collection<int, UserSession>
     */
    public function historyFor(User $user, int $limit = 10): Collection
    {
        return $user->sessions()->ended()->limit($limit)->get();
    }

    /**
     * Build the filtered ledger query the admin screens paginate.
     *
     * The owner is eager loaded because the table prevents lazy loading, and the
     * search box reaches through that relation.
     *
     * @param array{search?: string, status?: string, device?: string, from?: string, to?: string} $filters
     *
     * @return Builder<UserSession>
     */
    public function filtered(array $filters): Builder
    {
        return UserSession::query()
            ->with('user')
            ->when($filters['user'] ?? null, fn (Builder $query, User $user) => $query->forUser($user))
            ->when(
                $filters['status'] ?? null,
                fn (Builder $query, string $status) => $status === 'ended' ? $query->ended() : $query->active()
            )
            ->when($filters['device'] ?? null, fn (Builder $query, string $device) => $query->where('device_type', $device))
            ->when($filters['search'] ?? null, function (Builder $query, string $search) {
                $query->whereHas('user', function (Builder $userQuery) use ($search): void {
                    $userQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($filters['from'] ?? null, fn (Builder $query, string $from) => $query->where('last_seen_at', '>=', $from))
            ->when($filters['to'] ?? null, fn (Builder $query, string $to) => $query->where('last_seen_at', '<=', $to.' 23:59:59'))
            ->orderByDesc('last_seen_at');
    }

    /**
     * Revoke a single session and close its ledger row.
     */
    public function revoke(UserSession $session, SessionEndReason $reason, ?Authenticatable $revoker = null): int
    {
        return $this->revokeMany(collect([$session]), $reason, $revoker);
    }

    /**
     * Revoke a set of sessions, returning how many were closed.
     */
    public function revokeMany(Collection $sessions, SessionEndReason $reason, ?Authenticatable $revoker = null): int
    {
        if ($sessions->isEmpty()) {
            return 0;
        }

        $nativeIds = $sessions->pluck('session_id')->filter()->values()->all();

        if ($nativeIds !== []) {
            DB::table(config('session.table', 'sessions'))->whereIn('id', $nativeIds)->delete();
        }

        // A bulk update bypasses casts, so the enum is written as its backing value.
        UserSession::whereIn('id', $sessions->pluck('id'))
            ->whereNull('ended_at')
            ->update([
                'ended_at' => now(),
                'end_reason' => $reason->value,
                'revoked_by' => $revoker?->getKey(),
            ]);

        return $sessions->count();
    }

    /**
     * Revoke every live session belonging to a user except one session id.
     */
    public function revokeOthersFor(User $user, ?string $exceptSessionId, SessionEndReason $reason, ?Authenticatable $revoker = null): int
    {
        $sessions = UserSession::active()
            ->forUser($user)
            ->when(
                $exceptSessionId,
                fn ($query) => $query->where('session_id', '!=', $exceptSessionId)
            )
            ->get();

        return $this->revokeMany($sessions, $reason, $revoker);
    }

    /**
     * Close the ledger row for the session the user is signing out of.
     *
     * The framework destroys its own row, so only the ledger needs updating here.
     */
    public function closeCurrentSession(Request $request, SessionEndReason $reason = SessionEndReason::LOGOUT): void
    {
        if (! $request->hasSession()) {
            return;
        }

        $sessionId = $request->session()->getId();

        if ($sessionId === '') {
            return;
        }

        $this->closeSession($sessionId, $reason);
    }

    /**
     * Close the ledger row recorded against a specific session id.
     */
    public function closeSession(string $sessionId, SessionEndReason $reason): void
    {
        UserSession::where('session_id', $sessionId)->first()?->close($reason);
    }

    /**
     * Reconcile sessions the framework let lapse and drop history past retention.
     *
     * @return array{closed: int, purged: int}
     */
    public function prune(?int $retentionDays = null): array
    {
        $retentionDays ??= (int) config('user-sessions.history_retention_days');

        // Rows idle past the framework lifetime are already dead even though the
        // driver only garbage-collects them lazily. They are closed as of the last
        // moment we saw them, which is the most truthful timestamp available.
        $closed = UserSession::stale()->update([
            'ended_at' => DB::raw('last_seen_at'),
            'end_reason' => SessionEndReason::EXPIRED->value,
        ]);

        $purged = UserSession::whereNotNull('ended_at')
            ->where('ended_at', '<', now()->subDays($retentionDays))
            ->delete();

        return ['closed' => $closed, 'purged' => $purged];
    }

    /**
     * Revoke a user's other sessions after a password change, when enabled.
     */
    public function handlePasswordChanged(User $user, ?string $currentSessionId, ?Authenticatable $actor = null): int
    {
        if (! config('user-sessions.revoke_on_password_change')) {
            return 0;
        }

        return $this->revokeOthersFor($user, $currentSessionId, SessionEndReason::PASSWORD_CHANGED, $actor ?? $user);
    }

    /**
     * Session key under which the Login event leaves metadata for this service.
     */
    public function handoffKey(): string
    {
        return (string) config('user-sessions.handoff_key');
    }
}
