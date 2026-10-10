<?php

use App\Actions\Auth\ResolvePasskeyLoginFailure;
use App\Http\Middleware\EnsureConsentRecorded;
use App\Http\Middleware\EnsureEmailIsVerifiedWhenRequired;
use App\Http\Middleware\EnsureRegistrationIsEnabled;
use App\Http\Middleware\ShareThemePreference;
use App\Http\Middleware\TrackUserSession;
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
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware('web')
                ->group(base_path('routes/admin.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Added by EnvKit so shared (public) URLs keep the https scheme and
        // public host. Safe locally; remove to opt out.
        $middleware->trustProxies(at: '*');
        $middleware->web(append: [
            ShareThemePreference::class,
            TrackUserSession::class,
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
