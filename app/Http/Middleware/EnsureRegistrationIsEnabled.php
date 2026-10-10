<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\CompanySetting;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Returns 404 for Fortify's registration routes while the `enable_registration`
 * company setting is off.
 *
 * The setting lives in the database, so it cannot be read from `config/fortify.php`
 * to drop `Features::registration()` at boot. The routes therefore always exist and
 * the gate is evaluated per request instead.
 */
final class EnsureRegistrationIsEnabled
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->routeIs('register', 'register.store')) {
            return $next($request);
        }

        $enabled = filter_var(CompanySetting::get('enable_registration', '0'), FILTER_VALIDATE_BOOLEAN);

        abort_unless($enabled, 404);

        return $next($request);
    }
}
