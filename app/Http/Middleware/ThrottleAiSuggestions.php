<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

/**
 * Cap how often one person can ask for a fresh batch of AI suggestions.
 *
 * This is a cost ceiling as much as an abuse control: every call spends tokens
 * and sends draft text to a third party, so an editor holding down the button
 * must not be able to turn one click into a hundred requests. The counter is
 * keyed per authenticated user and IP and deliberately sits apart from
 * ThrottleUsernameAvailability, whose limits protect a different oracle.
 */
final class ThrottleAiSuggestions
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     *
     * @return Response The next response, or a JSON 429 with empty result sets.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $limit = max(1, (int) config('ai.rate_limit', 10));
        $key = 'ai-suggestions|'.(string) $request->user()?->getAuthIdentifier().'|'.$request->ip();

        if (RateLimiter::tooManyAttempts($key, $limit)) {
            return response()->json([
                'message' => 'Too many suggestion requests. Please wait '.RateLimiter::availableIn($key).' seconds.',
                'titles' => [],
                'usernames' => [],
            ], 429);
        }

        RateLimiter::hit($key, 60);

        return $next($request);
    }
}
