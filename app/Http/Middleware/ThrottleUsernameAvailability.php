<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

/**
 * Cap how fast the forms that carry a username can be asked whether a name is taken.
 *
 * A `unique` rule is an oracle: one POST returns "that name exists" and another does not,
 * so an unthrottled form can walk a wordlist of names — and usernames are guessable in a
 * way email addresses are not, since most of them are somebody's email local part. The
 * login form has always had a ceiling; these forms had none.
 *
 * Applied by name to the three routes that validate a username, rather than as `throttle:`
 * middleware on each, because registration belongs to Fortify and its middleware list is
 * shared by every Fortify route — adding a limiter there would also put one on sign-in.
 *
 * Keyed per surface, so a person who mistypes a name three times on their profile does not
 * find registration locked as well.
 */
final class ThrottleUsernameAvailability
{
    /**
     * The routes this limiter covers, and how many attempts each allows per minute.
     *
     * Ten is well above the number of genuine attempts anyone makes and well below a rate
     * a sweep is worth running at.
     */
    private const LIMITS = [
        'register.store' => 10,
        'admin.profile.update' => 10,
        'admin.users.store' => 20,
        'admin.users.update' => 20,
    ];

    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     *
     * @throws ValidationException
     */
    public function handle(Request $request, Closure $next): Response
    {
        $route = $request->route()?->getName();

        if ($route === null || ! isset(self::LIMITS[$route])) {
            return $next($request);
        }

        $key = $route.'|'.(string) $request->user()?->getAuthIdentifier().'|'.$request->ip();

        if (RateLimiter::tooManyAttempts($key, self::LIMITS[$route])) {
            throw ValidationException::withMessages([
                'username' => ['Too many attempts. Please wait a minute before trying again.'],
            ]);
        }

        // Counted on the way in, not only on a rejection: the answer this form gives about
        // a name is the thing being limited, whether or not the rest of the submit passes.
        RateLimiter::hit($key, 60);

        return $next($request);
    }
}
