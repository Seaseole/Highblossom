<?php

declare(strict_types=1);

namespace App\Domains\Seo\Services;

use App\Domains\Seo\Contracts\HasSeoInterface;
use App\Domains\Seo\DataTransferObjects\SeoMetadata;
use App\Domains\Content\Models\SeoStaticRoute;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Route;

final class SeoInjectionService
{
    private ?SeoMetadata $currentMetadata = null;

    public function __construct(
        private readonly string $siteName,
        private readonly string $separator,
        private readonly ?string $defaultOgImage,
    ) {}

    public function registerViewComposer(): void
    {
        View::composer('*', function ($view) {
            if ($this->currentMetadata === null) {
                $this->currentMetadata = $this->resolveMetadata();
            }

            $view->with('seoMetadata', $this->currentMetadata);
        });
    }

    public function setMetadata(SeoMetadata $metadata): void
    {
        $this->currentMetadata = $metadata;
    }

    public function getMetadata(): SeoMetadata
    {
        if ($this->currentMetadata === null) {
            $this->currentMetadata = $this->resolveMetadata();
        }

        return $this->currentMetadata;
    }

    private function resolveMetadata(): SeoMetadata
    {
        $routeName = Route::currentRouteName();

        // 1. Check if view has a model bound
        $viewData = View::getShared();
        foreach ($viewData as $value) {
            if ($value instanceof HasSeoInterface) {
                return $this->enhanceMetadata($value->getSeoMetadata(), $value->getCanonicalUrl());
            }
        }

        // 2. Check for static route SEO
        if ($routeName !== null) {
            $staticRoute = SeoStaticRoute::where('route_name', $routeName)->first();
            if ($staticRoute !== null) {
                return $this->enhanceMetadata($staticRoute->toMetadata(), null);
            }
        }

        // 3. Return defaults
        return $this->getDefaultMetadata($routeName);
    }

    private function enhanceMetadata(SeoMetadata $metadata, ?string $canonicalUrl): SeoMetadata
    {
        $enhanced = $metadata->toArray();

        // Build full title with site name
        if ($enhanced['meta_title'] !== null && $enhanced['meta_title'] !== '') {
            $enhanced['meta_title'] = $enhanced['meta_title'] . ' ' . $this->separator . ' ' . $this->siteName;
        } else {
            $enhanced['meta_title'] = $this->siteName;
        }

        // Set canonical URL
        if ($canonicalUrl !== null && $enhanced['canonical_url'] === null) {
            $enhanced['canonical_url'] = $canonicalUrl;
        }

        // Default OG image
        if ($enhanced['og_image'] === null && $this->defaultOgImage !== null) {
            $enhanced['og_image'] = $this->defaultOgImage;
        }

        // Sync OG from meta if not set
        if ($enhanced['og_title'] === null && $enhanced['meta_title'] !== null) {
            $enhanced['og_title'] = $enhanced['meta_title'];
        }
        if ($enhanced['og_description'] === null && $enhanced['meta_description'] !== null) {
            $enhanced['og_description'] = $enhanced['meta_description'];
        }

        // Sync Twitter from OG
        if ($enhanced['twitter_title'] === null && $enhanced['og_title'] !== null) {
            $enhanced['twitter_title'] = $enhanced['og_title'];
        }
        if ($enhanced['twitter_description'] === null && $enhanced['og_description'] !== null) {
            $enhanced['twitter_description'] = $enhanced['og_description'];
        }
        if ($enhanced['twitter_image'] === null && $enhanced['og_image'] !== null) {
            $enhanced['twitter_image'] = $enhanced['og_image'];
        }

        return SeoMetadata::fromArray($enhanced);
    }

    private function getDefaultMetadata(?string $routeName): SeoMetadata
    {
        return new SeoMetadata(
            metaTitle: $this->siteName,
            ogTitle: $this->siteName,
            ogImage: $this->defaultOgImage,
            twitterCard: 'summary_large_image',
        );
    }

    public function clearMetadata(): void
    {
        $this->currentMetadata = null;
    }
}
