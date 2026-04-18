<?php

declare(strict_types=1);

namespace App\Domains\Seo\Traits;

use App\Domains\Seo\DataTransferObjects\SeoMetadata;
use Illuminate\Support\Facades\URL;

trait HasSeo
{
    abstract public function seoDefaults(): array;

    public function getSeoMetadata(): SeoMetadata
    {
        $stored = $this->getAttribute('seo_metadata') ?? [];
        $defaults = $this->seoDefaults();

        $merged = array_merge($defaults, $stored);

        return SeoMetadata::fromArray($merged);
    }

    public function updateSeo(array $data): void
    {
        $current = $this->getAttribute('seo_metadata') ?? [];
        $this->update(['seo_metadata' => array_merge($current, $data)]);
    }

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

    public function getSeoTitle(): string
    {
        $metadata = $this->getSeoMetadata();

        return $metadata->metaTitle ?? $this->getAttribute('title') ?? $this->getAttribute('name') ?? '';
    }

    public function getSeoDescription(): string
    {
        $metadata = $this->getSeoMetadata();

        return $metadata->metaDescription ?? $this->getAttribute('short_description') ?? $this->getAttribute('description') ?? '';
    }

    public function shouldIndex(): bool
    {
        $metadata = $this->getSeoMetadata();

        return ! $metadata->noIndex;
    }

    public function getSitemapPriority(): float
    {
        return $this->getSeoMetadata()->priority;
    }

    public function getSitemapChangefreq(): string
    {
        return $this->getSeoMetadata()->changefreq;
    }

    public function getLastModified(): ?\DateTime
    {
        return $this->updated_at ?? $this->created_at ?? null;
    }
}
