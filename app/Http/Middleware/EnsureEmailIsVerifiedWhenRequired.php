<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\CompanySetting;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Redirect authenticated users to the email verification notice when the
 * `require_email_verification` company setting is on and their address
 * is not yet verified. Inactive when the setting is off.
 */
final class EnsureEmailIsVerifiedWhenRequired
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        $required = filter_var(CompanySetting::get('require_email_verification', '0'), FILTER_VALIDATE_BOOLEAN);

        if ($user === null || ! $required || $user->hasVerifiedEmail()) {
            return $next($request);
        }

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Your email address is not verified.'], 403);
        }

        return redirect()->route('verification.notice');
    }
}
