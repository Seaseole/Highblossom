<?php

use App\Http\Middleware\ShareThemePreference;
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
        ]);

        $middleware->alias([
            'role' => RoleMiddleware::class,
            'permission' => PermissionMiddleware::class,
            'role_or_permission' => RoleOrPermissionMiddleware::class,
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

            return response()->json([
                'message' => collect($e->errors())->flatten()->first() ?? $e->getMessage(),
                'errors' => $e->errors(),
            ], $e->status());
        });
    })->create();
