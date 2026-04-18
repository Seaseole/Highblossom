<?php

namespace App\Providers;

use App\Domains\Content\Models\CompanySetting;
use App\Domains\Content\Models\ContactNumber;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

use App\Domains\Content\Models\Post;
use App\Domains\Content\Policies\PostPolicy;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            \App\Infrastructure\Contracts\AvailabilityServiceInterface::class,
            \App\Infrastructure\Services\AvailabilityService::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
       
        $this->configureDefaults();

        Model::preventLazyLoading(! app()->isProduction());

        // Fix for MySQL index length limit with utf8mb4
        Schema::defaultStringLength(191);

        Blade::anonymousComponentPath(resource_path('views/layouts'), 'layouts');

        // Register policies
        Gate::policy(Post::class, PostPolicy::class);

        // Share company settings globally (with fallback for when database doesn't exist yet)
        try {
            View::share('companyName', CompanySetting::get('company_name', 'Highblossom PTY LTD'));
            View::share('logoText', CompanySetting::get('logo_text', 'Highblossom PTY LTD'));
            View::share('primaryEmail', CompanySetting::get('primary_email', 'info@highblossom.co.bw'));
            View::share('companyAddress', CompanySetting::get('address', 'Plot 123, Main Road, Broadhurst, Gaborone, Botswana'));

            // Use view composers for objects that need to be resolved per-request
            view()->composer('*', function ($view) {
                $view->with('primaryPhone', ContactNumber::active()->primary()->first());
                $view->with('whatsappNumber', ContactNumber::active()->whatsapp()->first());
                $view->with('contactNumbers', ContactNumber::active()->ordered()->get());
            });
        } catch (\Exception $e) {
            // Database doesn't exist yet (during migrations), use defaults
            View::share('companyName', 'Highblossom PTY LTD');
            View::share('logoText', 'Highblossom PTY LTD');
            View::share('primaryEmail', 'info@highblossom.co.bw');
            View::share('companyAddress', 'Plot 123, Main Road, Broadhurst, Gaborone, Botswana');

            view()->composer('*', function ($view) {
                $view->with('primaryPhone', null);
                $view->with('whatsappNumber', null);
                $view->with('contactNumbers', collect());
            });
        }
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

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null,
        );
        //  DB::schema()->defaultStringLength(191);
    }
}
