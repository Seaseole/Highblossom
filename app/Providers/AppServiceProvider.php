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

        // Share company settings globally with fallback defaults
        try {
            $settings = [
                'companyName' => CompanySetting::get('company_name', 'Highblossom PTY LTD'),
                'logoText' => CompanySetting::get('logo_text', 'Highblossom'),
                'primaryEmail' => CompanySetting::get('primary_email', 'info@highblossom.co.bw'),
                'companyAddress' => CompanySetting::get('address', 'Plot 123, Main Road, Broadhurst, Gaborone, Botswana'),
                'primaryPhone' => CompanySetting::get('primary_phone', '+267 123 4567'),
                'whatsappDefault' => CompanySetting::get('whatsapp_number_default', '+267 123 4567'),
                'whatsappAdditional' => CompanySetting::get('whatsapp_additional_numbers', []),
                'workingHours' => CompanySetting::get('working_hours', [
                    'monday' => ['open' => '08:00', 'close' => '17:00', 'is_closed' => false],
                    'tuesday' => ['open' => '08:00', 'close' => '17:00', 'is_closed' => false],
                    'wednesday' => ['open' => '08:00', 'close' => '17:00', 'is_closed' => false],
                    'thursday' => ['open' => '08:00', 'close' => '17:00', 'is_closed' => false],
                    'friday' => ['open' => '08:00', 'close' => '17:00', 'is_closed' => false],
                    'saturday' => ['open' => '08:00', 'close' => '12:00', 'is_closed' => false],
                    'sunday' => ['open' => null, 'close' => null, 'is_closed' => true],
                ]),
                'timeFormatDisplay' => CompanySetting::get('time_format_display', '12'),
                'businessLogo' => CompanySetting::get('business_logo', ''),
                'favicon' => CompanySetting::get('favicon', ''),
                'googleMapsApiKey' => CompanySetting::get('google_maps_api_key', ''),
                'mapDirectionsLink' => CompanySetting::get('map_directions_link', 'https://maps.app.goo.gl/KJip8MytQrPrULg58'),
                'timezone' => CompanySetting::get('timezone', 'Africa/Gaborone'),
                'locale' => CompanySetting::get('locale', 'en_GB'),
                'dateFormat' => CompanySetting::get('date_format', 'd/M/Y'),
                'timeFormat' => CompanySetting::get('time_format', 'H:i'),
                'currencySymbol' => CompanySetting::get('currency_symbol', 'P'),
                'facebookUrl' => CompanySetting::get('facebook_url', ''),
                'instagramUrl' => CompanySetting::get('instagram_url', ''),
                'linkedinUrl' => CompanySetting::get('linkedin_url', ''),
                'announcementActive' => CompanySetting::get('announcement_active', false),
                'announcementText' => CompanySetting::get('announcement_text', ''),
                'announcementLink' => CompanySetting::get('announcement_link', ''),
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
            View::share('announcementActive', false);
            View::share('announcementText', '');
            View::share('announcementLink', '');
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
    }

    public static function passwordRules(): Password
    {
        return Password::min(8)
            ->letters()
            ->mixedCase()
            ->numbers()
            ->symbols();
    }
}
