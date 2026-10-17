<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Enums\DeviceType;
use App\Enums\SessionEndReason;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RevokeSessionRequest;
use App\Http\Requests\Admin\SessionFilterRequest;
use App\Models\User;
use App\Models\UserSession;
use App\Services\SessionManager;
use App\Services\UserSessionService;
use App\Support\SystemOverview\ProbeRunner;
use Auditify\Facades\Auditify;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Administrator view of every session in the application.
 */
final class UserSessionController extends Controller
{
    public function __construct(
        private readonly UserSessionService $sessions,
    ) {}

    /**
     * List sessions across all users.
     */
    public function index(SessionFilterRequest $request): View
    {
        return $this->listing('admin.sessions.index', $request->filters());
    }

    /**
     * List the sessions of one user.
     */
    public function user(User $user, SessionFilterRequest $request): View
    {
        return $this->listing('admin.sessions.user', $request->filters(), $user);
    }

    /**
     * Sign out a single session belonging to any user.
     */
    public function revoke(UserSession $session, RevokeSessionRequest $request): RedirectResponse
    {
        if ($session->isCurrent()) {
            return back()->withErrors(['session' => __('admin-sessions.cannot_revoke_current')]);
        }

        $this->sessions->revoke($session, SessionEndReason::REVOKED_BY_ADMIN, $request->user());

        return back()->with('success', __('admin-sessions.session_revoked'));
    }

    /**
     * Sign out every live session of a user, sparing the administrator's own.
     */
    public function revokeForUser(User $user, RevokeSessionRequest $request): RedirectResponse
    {
        $count = $this->sessions->revokeOthersFor(
            $user,
            $request->session()->getId(),
            SessionEndReason::REVOKED_BY_ADMIN,
            $request->user()
        );

        return back()->with('success', __('admin-sessions.sessions_revoked_by_admin', ['count' => $count]));
    }

    /**
     * Run the session reconciliation now, sparing the session doing the asking.
     *
     * The same code path the hourly schedule takes, so the button cannot produce a
     * different result from the job.
     */
    public function prune(Request $request, SessionManager $sessionManager, ProbeRunner $probes): RedirectResponse
    {
        $result = $sessionManager->prune(exceptSessionId: $request->session()->getId());

        Auditify::logActivity('Prune sessions', null, null, [
            'deleted' => $result['deleted'],
            'closed' => $result['closed'],
            'expired' => $result['expired'],
            'orphaned' => $result['orphaned'],
            'idle' => $result['idle'],
            'purged' => $result['purged'],
        ]);

        // The card caches its measurement, so re-measure it or the redirect renders
        // the numbers from before the prune.
        $probes->warm('sessions');

        return back()->with('success', __('messages.system_overview_pruned', [
            'deleted' => $result['deleted'],
            'closed' => $result['closed'],
        ]));
    }

    /**
     * Render the session list, scoped to one account when an account is given.
     *
     * @param array<string, mixed> $filters
     */
    private function listing(string $view, array $filters, ?User $user = null): View
    {
        if ($user !== null) {
            $filters['user'] = $user;
        }

        return view($view, [
            'account' => $user,
            'activeCount' => $this->sessions->filtered(array_merge($filters, ['status' => 'active']))->count(),
            'deviceTypes' => DeviceType::cases(),
            'filters' => $filters,
            'sessions' => $this->sessions->filtered($filters)
                ->paginate((int) config('user-sessions.per_page'))
                ->withQueryString(),
        ]);
    }
}
