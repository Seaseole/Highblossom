<?php

declare(strict_types=1);

namespace App\Providers;

use App\Services\PasswordPolicy;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

/**
 * Password rules service provider.
 *
 * Registers the application's password policy once, so every form that validates with
 * Password::default() — registration, the reset link, the Security page and the admin
 * user forms — answers with the length and composition the administrator configured.
 * The callback is read per validation rather than at boot, which is what lets a saved
 * setting take effect without restarting anything.
 */
class PasswordRulesServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(PasswordPolicy::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Password::defaults(fn (): Password => app(PasswordPolicy::class)->rule());
    }
}
