<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use App\Services\DataTransferObjects\SeoMetadata;
use Illuminate\Support\Facades\URL;

/**
 * SEO concern for models that require metadata, canonical URLs, and sitemap support.
 */
trait HasSeo
{
    abstract public function seoDefaults(): array;

    /**
     * Get the merged SEO metadata from defaults and stored values.
     */
    public function getSeoMetadata(): SeoMetadata
    {
        $stored = $this->getAttribute('seo_metadata') ?? [];
        $defaults = $this->seoDefaults();

        $merged = array_merge($defaults, $stored);

        return SeoMetadata::fromArray($merged);
    }

    /**
     * Merge new SEO data into the stored metadata.
     */
    public function updateSeo(array $data): void
    {
        $current = $this->getAttribute('seo_metadata') ?? [];
        $this->update(['seo_metadata' => array_merge($current, $data)]);
    }

    /**
     * Get the canonical URL from stored metadata or generate from route.
     */
    public function getCanonicalUrl(): string
    {
        $metadata = $this->getSeoMetadata();

        if ($metadata->canonicalUrl !== null) {
            return $metadata->canonicalUrl;
        }

        return URL::route($this->getRouteName(), $this->getRouteParameters(), true);
    }

    abstract protected function getRouteName(): string;

    abstract protected function getRouteParameters(): array;

    /**
     * Get the SEO meta title.
     */
    public function getSeoTitle(): string
    {
        $metadata = $this->getSeoMetadata();

        return $metadata->metaTitle ?? $this->getAttribute('title') ?? $this->getAttribute('name') ?? '';
    }

    /**
     * Get the SEO meta description.
     */
    public function getSeoDescription(): string
    {
        $metadata = $this->getSeoMetadata();

        return $metadata->metaDescription ?? $this->getAttribute('short_description') ?? $this->getAttribute('description') ?? '';
    }

    /**
     * Determine if the page should be indexed by search engines.
     */
    public function shouldIndex(): bool
    {
        $metadata = $this->getSeoMetadata();

        return ! $metadata->noIndex;
    }

    /**
     * Get the sitemap priority for this page.
     */
    public function getSitemapPriority(): float
    {
        return $this->getSeoMetadata()->priority;
    }

    /**
     * Get the sitemap change frequency for this page.
     */
    public function getSitemapChangefreq(): string
    {
        return $this->getSeoMetadata()->changefreq;
    }

    /**
     * Get the last modified date for sitemap.
     */
    public function getLastModified(): ?\DateTime
    {
        return $this->updated_at ?? $this->created_at ?? null;
    }
}
