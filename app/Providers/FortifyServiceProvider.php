<?php

declare(strict_types=1);

namespace App\Providers;

use App\Actions\Fortify\AuthenticateSignIn;
use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Http\Requests\Auth\LoginRequest as AppLoginRequest;
use App\Http\Requests\Auth\SendPasswordResetLinkRequest as AppSendPasswordResetLinkRequest;
use App\Http\Responses\FailedPasswordConfirmationResponse as AppFailedPasswordConfirmationResponse;
use App\Http\Responses\NeutralPasswordResetLinkRequestResponse;
use App\Support\CanonicalLoginRateLimiter;
use App\Support\SignInIdentifier;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Contracts\FailedPasswordConfirmationResponse as FortifyFailedPasswordConfirmationResponseContract;
use Laravel\Fortify\Contracts\FailedPasswordResetLinkRequestResponse as FortifyFailedPasswordResetLinkRequestResponseContract;
use Laravel\Fortify\Fortify;
use Laravel\Fortify\Http\Requests\LoginRequest as FortifyLoginRequest;
use Laravel\Fortify\Http\Requests\SendPasswordResetLinkRequest as FortifySendPasswordResetLinkRequest;
use Laravel\Fortify\LoginRateLimiter;

/**
 * Fortify service provider.
 *
 * Configures Laravel Fortify authentication, views, and rate limiting for the application.
 */
class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * Fortify's controllers type-hint its own request classes, which validate almost
     * nothing (`required|string` on sign-in, `required|email` on the reset link) and
     * answer with Laravel's default sentences. Resolving those abstracts to the
     * application's subclasses is the only way to own the copy without copying the
     * controllers, and it leaves every other Fortify request untouched. The password
     * confirmation step has no request class, so its answer is replaced at the contract
     * instead. This provider registers after Fortify's, so these bindings win.
     */
    public function register(): void
    {
        $this->app->bind(FortifyLoginRequest::class, AppLoginRequest::class);
        $this->app->bind(FortifySendPasswordResetLinkRequest::class, AppSendPasswordResetLinkRequest::class);
        $this->app->singleton(FortifyFailedPasswordConfirmationResponseContract::class, AppFailedPasswordConfirmationResponse::class);

        // The unknown-account answer to a reset request, replaced with the same sentence the
        // known-account answer gives. Contract binding, same reason as the one above.
        $this->app->singleton(
            FortifyFailedPasswordResetLinkRequestResponseContract::class,
            NeutralPasswordResetLinkRequestResponse::class
        );

        // Fortify resolves its own LoginRateLimiter from the container in four places, and
        // its throttleKey() is what decides the lockout, so the subclass has to be bound at
        // the parent's name rather than referenced anywhere.
        $this->app->singleton(LoginRateLimiter::class, CanonicalLoginRateLimiter::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureActions();
        $this->configureViews();
        $this->configureRateLimiting();
    }

    /**
     * Configure Fortify actions.
     */
    private function configureActions(): void
    {
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);
        Fortify::createUsersUsing(CreateNewUser::class);

        // Replaces the guard's credential attempt, so the one sign-in field can be read as
        // either an email address or a username. Both the plain login and the two-factor
        // challenge ask for this callback, which is why they cannot drift apart.
        Fortify::authenticateUsing(fn (Request $request) => app(AuthenticateSignIn::class)->handle($request));
    }

    /**
     * Configure Fortify views.
     */
    private function configureViews(): void
    {
        Fortify::loginView(fn () => view('auth.login'));
        Fortify::verifyEmailView(fn () => view('auth.verify-email'));
        Fortify::twoFactorChallengeView(fn () => view('auth.two-factor-challenge'));
        Fortify::confirmPasswordView(fn () => view('auth.confirm-password'));
        Fortify::registerView(fn () => view('auth.register'));
        Fortify::resetPasswordView(fn (Request $request) => view('auth.reset-password', ['token' => $request->route('token')]));
        Fortify::requestPasswordResetLinkView(fn () => view('auth.forgot-password'));
    }

    /**
     * Configure rate limiting.
     */
    private function configureRateLimiting(): void
    {
        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(5)->by(SignInIdentifier::throttleKey(
                (string) $request->input(Fortify::username()),
                $request->ip() ?? ''
            ));
        });
    }
}
