<?php

namespace App\Providers;

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
use Opcodes\LogViewer\Facades\LogViewer;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(
            \App\Services\Contracts\AvailabilityServiceInterface::class,
            \App\Services\AvailabilityService::class
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

        // Share company settings globally with fallback defaults
        try {
            $settings = [
                'companyName' => \App\Models\CompanySetting::get('company_name', 'Highblossom PTY LTD'),
                'logoText' => \App\Models\CompanySetting::get('logo_text', 'Highblossom'),
                'primaryEmail' => \App\Models\CompanySetting::get('primary_email', 'info@highblossom.co.bw'),
                'companyAddress' => \App\Models\CompanySetting::get('address', 'Plot 123, Main Road, Broadhurst, Gaborone, Botswana'),
                'primaryPhone' => \App\Models\CompanySetting::get('primary_phone', '+267 123 4567'),
                'whatsappDefault' => \App\Models\CompanySetting::get('whatsapp_number_default', '+267 123 4567'),
                'whatsappAdditional' => \App\Models\CompanySetting::get('whatsapp_additional_numbers', []),
                'workingHours' => \App\Models\CompanySetting::get('working_hours', [
                    'monday' => ['open' => '08:00', 'close' => '17:00', 'is_closed' => false],
                    'tuesday' => ['open' => '08:00', 'close' => '17:00', 'is_closed' => false],
                    'wednesday' => ['open' => '08:00', 'close' => '17:00', 'is_closed' => false],
                    'thursday' => ['open' => '08:00', 'close' => '17:00', 'is_closed' => false],
                    'friday' => ['open' => '08:00', 'close' => '17:00', 'is_closed' => false],
                    'saturday' => ['open' => '08:00', 'close' => '12:00', 'is_closed' => false],
                    'sunday' => ['open' => null, 'close' => null, 'is_closed' => true],
                ]),
                'timeFormatDisplay' => \App\Models\CompanySetting::get('time_format_display', '12'),
                'businessLogo' => \App\Models\CompanySetting::get('business_logo', ''),
                'favicon' => \App\Models\CompanySetting::get('favicon', ''),
                'googleMapsApiKey' => \App\Models\CompanySetting::get('google_maps_api_key', ''),
                'mapDirectionsLink' => \App\Models\CompanySetting::get('map_directions_link', 'https://maps.app.goo.gl/KJip8MytQrPrULg58'),
                'timezone' => \App\Models\CompanySetting::get('timezone', 'Africa/Gaborone'),
                'locale' => \App\Models\CompanySetting::get('locale', 'en_GB'),
                'dateFormat' => \App\Models\CompanySetting::get('date_format', 'd/M/Y'),
                'timeFormat' => \App\Models\CompanySetting::get('time_format', 'H:i'),
                'currencySymbol' => \App\Models\CompanySetting::get('currency_symbol', 'P'),
                'facebookUrl' => \App\Models\CompanySetting::get('facebook_url', ''),
                'instagramUrl' => \App\Models\CompanySetting::get('instagram_url', ''),
                'linkedinUrl' => \App\Models\CompanySetting::get('linkedin_url', ''),
            ];

            foreach ($settings as $key => $value) {
                View::share($key, $value);
            }
        } catch (\Exception $e) {
            // Basic fallbacks for when database isn't ready
            View::share('companyName', 'Highblossom PTY LTD');
            View::share('logoText', 'Highblossom');
            View::share('primaryEmail', 'info@highblossom.co.bw');
            View::share('companyAddress', 'Plot 123, Main Road, Broadhurst, Gaborone, Botswana');
            View::share('primaryPhone', '+267 123 4567');
        }

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

        Password::defaults(function () {
            $rule = Password::min(8);

            return app()->isProduction()
                ? $rule->min(12)
                    ->max(64)
                    ->mixedCase()
                    ->letters()
                    ->numbers()
                    ->symbols()
                    // ->uncompromised()
                : $rule;
        });
    }
}
