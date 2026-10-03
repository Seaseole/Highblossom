<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Enums\SessionEndReason;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RevokeOwnSessionRequest;
use App\Models\UserSession;
use App\Services\UserSessionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Let an account owner sign their other devices out from the profile page.
 */
final class ProfileSessionController extends Controller
{
    public function __construct(
        private readonly UserSessionService $sessions,
    ) {}

    /**
     * Revoke one of the authenticated user's own sessions.
     */
    public function destroy(RevokeOwnSessionRequest $request, UserSession $session): RedirectResponse
    {
        if ($session->isCurrent()) {
            return back()->withErrors(['session' => __('admin-sessions.cannot_revoke_current')]);
        }

        $this->sessions->revoke($session, SessionEndReason::REVOKED_BY_USER, $request->user());

        return back()->with('success', __('admin-sessions.session_revoked'));
    }

    /**
     * Revoke every session of the authenticated user except the one serving this request.
     */
    public function revokeOthers(Request $request): RedirectResponse
    {
        $count = $this->sessions->revokeOthersFor(
            $request->user(),
            $request->session()->getId(),
            SessionEndReason::REVOKED_BY_USER,
            $request->user()
        );

        return back()->with('success', __('admin-sessions.sessions_revoked', ['count' => $count]));
    }
}
