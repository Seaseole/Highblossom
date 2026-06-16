<?php

declare(strict_types=1);

namespace App\Providers;

use App\Services\SeoInjectionService;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;

/**
 * Service provider for SEO-related services.
 *
 * Registers the SeoInjectionService singleton and boots its view composer.
 */
final class SeoServiceProvider extends ServiceProvider
{
    /**
     * Register SEO application services.
     *
     * Binds SeoInjectionService as a singleton with configured site name, separator, and OG image.
     */
    public function register(): void
    {
        $this->app->singleton(SeoInjectionService::class, function () {
            return new SeoInjectionService(
                siteName: Config::get('seo.site_name', Config::get('app.name')),
                separator: Config::get('seo.separator', '|'),
                defaultOgImage: Config::get('seo.default_og_image'),
            );
        });
    }

    /**
     * Bootstrap SEO services.
     *
     * Resolves the SeoInjectionService and registers the view composer to inject SEO metadata.
     */
    public function boot(): void
    {
        /** @var SeoInjectionService $seoService */
        $seoService = $this->app->make(SeoInjectionService::class);
        $seoService->registerViewComposer();
    }
}
