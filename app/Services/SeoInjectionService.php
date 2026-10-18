<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Contracts\HasSeoInterface;
use App\Models\SeoStaticRoute;
use App\Services\Contact\ContactDetails;
use App\Services\DataTransferObjects\SeoMetadata;
use App\Services\Seo\LocalBusinessSchema;
use App\Services\Settings\SettingsManager;
use Highblossom\ContentBlocks\Support\MediaUrl;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;

final class SeoInjectionService
{
    /**
     * Routes that must never be indexed, whatever the SEO database says.
     *
     * A booking confirmation is a signed page holding one client's name, phone
     * number and vehicle, and the URL stays valid forever, so the guard belongs
     * in code rather than in a row an editor could delete.
     *
     * @var array<int, string>
     */
    private const NON_INDEXABLE_ROUTES = [
        'bookings.confirmation',
    ];

    private ?SeoMetadata $currentMetadata = null;

    public function __construct(
        private readonly string $siteName,
        private readonly string $separator,
        private readonly ?string $defaultOgImage,
        private readonly SettingsManager $settings,
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
        return $this->withBusinessSchema(
            $this->markPrivateRoutes($this->resolvePageMetadata())
        );
    }

    /**
     * Force noindex onto routes that carry personal data.
     */
    private function markPrivateRoutes(SeoMetadata $metadata): SeoMetadata
    {
        $routeName = Route::currentRouteName();

        if ($routeName === null || ! in_array($routeName, self::NON_INDEXABLE_ROUTES, true)) {
            return $metadata;
        }

        return $metadata->withOverrides([
            'no_index' => true,
            'canonical_url' => null,
            'robots' => null,
        ]);
    }

    /**
     * Attach the site's LocalBusiness node when the page did not bring its own.
     */
    private function withBusinessSchema(SeoMetadata $metadata): SeoMetadata
    {
        if ($metadata->schemaJsonLd !== null) {
            return $metadata;
        }

        $schema = LocalBusinessSchema::build($this->settings, ContactDetails::fromSettings($this->settings));

        return $schema === null ? $metadata : $metadata->withOverrides(['schema_json_ld' => $schema]);
    }

    private function resolvePageMetadata(): SeoMetadata
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
            $enhanced['meta_title'] = $enhanced['meta_title'].' '.$this->separator.' '.$this->siteName;
        } else {
            $enhanced['meta_title'] = $this->siteName;
        }

        // Set canonical URL
        if ($canonicalUrl !== null && $enhanced['canonical_url'] === null) {
            $enhanced['canonical_url'] = $canonicalUrl;
        }

        // Default OG image
        $defaultImage = $this->resolveDefaultOgImage();
        if ($enhanced['og_image'] === null && $defaultImage !== null) {
            $enhanced['og_image'] = $defaultImage;
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
        $metaTitle = $this->settings->get('seo_meta_title');
        $metaDescription = $this->settings->get('seo_meta_description');
        $metaKeywords = $this->settings->get('seo_meta_keywords');

        return new SeoMetadata(
            metaTitle: filled($metaTitle) ? $metaTitle : $this->siteName,
            metaDescription: filled($metaDescription) ? $metaDescription : null,
            metaKeywords: filled($metaKeywords) ? $metaKeywords : null,
            ogTitle: filled($metaTitle) ? $metaTitle : $this->siteName,
            ogDescription: filled($metaDescription) ? $metaDescription : null,
            ogImage: $this->resolveDefaultOgImage(),
            twitterCard: 'summary_large_image',
        );
    }

    /**
     * Resolve the site-wide OG image, preferring the admin-configured
     * meta image and falling back to the environment default.
     */
    private function resolveDefaultOgImage(): ?string
    {
        $image = $this->settings->get('seo_meta_image');

        if (filled($image)) {
            return MediaUrl::asset($image);
        }

        return $this->defaultOgImage;
    }

    public function clearMetadata(): void
    {
        $this->currentMetadata = null;
    }
}
