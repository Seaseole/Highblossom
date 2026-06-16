<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

/**
 * Password rules service provider.
 *
 * Sets default password validation rules (minimum length, mixed case, numbers, symbols).
 */
class PasswordRulesServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Password::defaults(function () {
            return Password::min(12)
                ->max(64)
                ->mixedCase()
                ->numbers()
                ->symbols();
        });
    }
}
