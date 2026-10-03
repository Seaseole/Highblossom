<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Redirect authenticated users to the consent capture page until they have
 * accepted both the terms and the privacy policy. Guests pass through — the
 * surrounding groups already require auth — and users with recorded consent
 * are never disturbed.
 */
final class EnsureConsentRecorded
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user === null || ($user->terms_accepted_at !== null && $user->privacy_accepted_at !== null)) {
            return $next($request);
        }

        if ($request->expectsJson()) {
            return response()->json(['message' => 'You must accept the terms and privacy policy.'], 403);
        }

        return redirect()->guest(route('consent'));
    }
}
