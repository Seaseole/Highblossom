<?php

declare(strict_types=1);

namespace App\Providers;

use App\Domains\Seo\Services\SeoInjectionService;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;

final class SeoServiceProvider extends ServiceProvider
{
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

    public function boot(): void
    {
        /** @var SeoInjectionService $seoService */
        $seoService = $this->app->make(SeoInjectionService::class);
        $seoService->registerViewComposer();
    }
}
