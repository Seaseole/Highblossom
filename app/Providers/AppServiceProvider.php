<?php

namespace App\Providers;

use App\Models\CompanySetting;
use App\Services\AvailabilityService;
use App\Services\Contracts\AvailabilityServiceInterface;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Opcodes\LogViewer\Facades\LogViewer;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(
            \App\Services\Settings\SettingsManager::class,
            \App\Services\Settings\SettingsManager::class
        );

        $this->app->singleton(
            AvailabilityServiceInterface::class,
            AvailabilityService::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        LogViewer::auth(function ($request) {
            return $request->user() && $request->user()->hasRole('Super Admin');
        });

        $this->configureDefaults();

        // Share global settings via View Composer
        View::composer('*', \App\Http\View\Composers\GlobalSettingsComposer::class);

        Model::preventLazyLoading(! app()->isProduction());

        // Fix for MySQL index length limit with utf8mb4
        Schema::defaultStringLength(191);

        Blade::anonymousComponentPath(resource_path('views/layouts'), 'layouts');
        Blade::anonymousComponentPath(resource_path('views/components/admin'), 'admin');

        // Grant Super Admin all permissions
        Gate::before(function ($user, $ability) {
            return $user->hasRole('Super Admin') ? true : null;
        });
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );
    }

    //    public static function passwordRules(): Password
    //    {
    //        return Password::min(12)
    //            ->letters()
    //            ->required()
    //            ->mixedCase()
    //            ->numbers()
    //            ->symbols();
    //    }
}
