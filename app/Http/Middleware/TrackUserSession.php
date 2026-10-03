<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Services\UserSessionService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Keeps the `user_sessions` device ledger in step with the live framework session.
 *
 * Runs its work after the pipeline unwinds on purpose: Fortify fires the Login
 * event and only then regenerates the session id, so recording during the event
 * would store an id that no longer corresponds to the user's cookie.
 */
final class TrackUserSession
{
    public function __construct(
        private readonly UserSessionService $sessions,
    ) {}

    /**
     * Handle an incoming request and record the session afterwards.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (Auth::check() && $request->hasSession()) {
            $this->sessions->track(Auth::user(), $request, Auth::viaRemember());
        }

        return $response;
    }
}
