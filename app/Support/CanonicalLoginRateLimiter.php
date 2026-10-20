<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Http\Request;
use Laravel\Fortify\Fortify;
use Laravel\Fortify\LoginRateLimiter;

/**
 * Fortify's login throttle, keyed on the account rather than the string typed.
 *
 * Fortify's own LoginRateLimiter builds its key from whatever was posted in the username
 * field, and now that the field takes either a username or an email address, two keys reach
 * one account: five failures against the address and five more against the name, because the
 * two strings hash to different buckets. This is the limiter that decides the lockout and
 * the seconds remaining in the banner, so overriding only the named `login` middleware would
 * leave the real behaviour unchanged.
 *
 * Bound over Laravel\Fortify\LoginRateLimiter in FortifyServiceProvider; that is the class
 * EnsureLoginIsNotThrottled, AttemptToAuthenticate, PrepareAuthenticatedSession and
 * LockoutResponse all ask the container for.
 */
final class CanonicalLoginRateLimiter extends LoginRateLimiter
{
    /**
     * Get the throttle key for the given request.
     */
    protected function throttleKey(Request $request): string
    {
        return SignInIdentifier::throttleKey(
            (string) $request->input(Fortify::username()),
            $request->ip() ?? ''
        );
    }
}
