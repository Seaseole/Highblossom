<?php

use App\Actions\Auth\ResolvePasskeyLoginFailure;
use App\Http\Middleware\EnsureConsentRecorded;
use App\Http\Middleware\EnsureEmailIsVerifiedWhenRequired;
use App\Http\Middleware\EnsureRegistrationIsEnabled;
use App\Http\Middleware\ShareThemePreference;
use App\Http\Middleware\ThrottleUsernameAvailability;
use App\Http\Middleware\TrackUserSession;
use App\Support\AdminSidebar;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware('web')
                ->group(base_path('routes/admin.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Added by EnvKit so shared (public) URLs keep the https scheme and
        // public host. Trusts loopback only (the tunnel/Octane proxy); remove to opt out.
        $middleware->trustProxies(at: ['127.0.0.1', '::1', '::ffff:127.0.0.1']);
        $middleware->web(append: [
            ShareThemePreference::class,
            TrackUserSession::class,
            ThrottleUsernameAvailability::class,
        ]);

        /* Which sidebar branches were left open is written by the browser, so it arrives as a
           plain cookie and Laravel's decryption would turn it into nothing at all. The server
           then rendered every branch that owns the page as open, Alpine closed the ones the
           person had shut a beat later, and that snap was the largest layout shift in the
           panel. Excluded rather than read out of $_COOKIE, because this is the mechanism the
           framework provides for exactly that case. The value is a map of booleans, so nothing
           sensitive is at stake in leaving it unencrypted. */
        $middleware->encryptCookies(except: [
            AdminSidebar::REMEMBER_COOKIE,
        ]);

        $middleware->alias([
            'role' => RoleMiddleware::class,
            'permission' => PermissionMiddleware::class,
            'role_or_permission' => RoleOrPermissionMiddleware::class,
            'verified.if_required' => EnsureEmailIsVerifiedWhenRequired::class,
            'consent.required' => EnsureConsentRecorded::class,
            'registration.enabled' => EnsureRegistrationIsEnabled::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Surface the specific validation reason (e.g. expired passkey challenge)
        // as the top-level JSON message the @laravel/passkeys client reads.
        $exceptions->render(function (ValidationException $e, Request $request) {
            $isPasskeyRoute = $request->expectsJson()
                && preg_match('#^(passkeys|user/passkeys)#', $request->path());

            if (! $isPasskeyRoute) {
                return null;
            }

            $failure = app(ResolvePasskeyLoginFailure::class)($request, $e);

            return response()->json([
                'message' => $failure['message']
                    ?? collect($e->errors())->flatten()->first()
                    ?? $e->getMessage(),
                'reason' => $failure['reason'] ?? null,
                'errors' => $e->errors(),
            ], $e->status);
        });
    })->create();
