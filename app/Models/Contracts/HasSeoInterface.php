<?php

declare(strict_types=1);

namespace App\Models\Contracts;

use App\Services\DataTransferObjects\SeoMetadata;

/**
 * Interface for models that support SEO metadata.
 */
interface HasSeoInterface
{
    /**
     * Get the SEO metadata for this model.
     */
    public function getSeoMetadata(): SeoMetadata;

    /**
     * Update SEO data for this model.
     *
     * @param array $data The SEO data to update
     */
    public function updateSeo(array $data): void;

    /**
     * Get default SEO values for this model.
     */
    public function seoDefaults(): array;

    /**
     * Get the canonical URL for this model.
     */
    public function getCanonicalUrl(): string;

    /**
     * Get the SEO title for this model.
     */
    public function getSeoTitle(): string;

    /**
     * Get the SEO description for this model.
     */
    public function getSeoDescription(): string;

    /**
     * Determine if this model should be indexed by search engines.
     */
    public function shouldIndex(): bool;
}
