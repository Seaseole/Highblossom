<?php

declare(strict_types=1);

namespace App\Services\DataTransferObjects;

use InvalidArgumentException;

/**
 * Data transfer object representing SEO metadata for a page.
 */
final readonly class SeoMetadata
{
    /**
     * @param float  $priority   Sitemap priority between 0.0 and 1.0
     * @param string $changefreq Sitemap change frequency
     *
     * @throws InvalidArgumentException When priority is out of range or changefreq is invalid
     */
    public function __construct(
        public ?string $metaTitle = null,
        public ?string $metaDescription = null,
        public ?string $metaKeywords = null,
        public ?string $ogTitle = null,
        public ?string $ogDescription = null,
        public ?string $ogImage = null,
        public string $ogType = 'website',
        public ?string $twitterCard = 'summary_large_image',
        public ?string $twitterTitle = null,
        public ?string $twitterDescription = null,
        public ?string $twitterImage = null,
        public ?string $canonicalUrl = null,
        public ?string $robots = null,
        public bool $noIndex = false,
        public float $priority = 0.5,
        public string $changefreq = 'monthly',
        public ?array $schemaJsonLd = null,
    ) {
        if ($this->priority < 0.0 || $this->priority > 1.0) {
            throw new InvalidArgumentException('Priority must be between 0.0 and 1.0');
        }

        $validChangefreqs = ['always', 'hourly', 'daily', 'weekly', 'monthly', 'yearly', 'never'];
        if (! in_array($this->changefreq, $validChangefreqs, true)) {
            throw new InvalidArgumentException('Invalid changefreq value');
        }
    }

    /**
     * Create a new instance from an associative array.
     */
    public static function fromArray(array $data): self
    {
        return new self(
            metaTitle: $data['meta_title'] ?? null,
            metaDescription: $data['meta_description'] ?? null,
            metaKeywords: $data['meta_keywords'] ?? null,
            ogTitle: $data['og_title'] ?? null,
            ogDescription: $data['og_description'] ?? null,
            ogImage: $data['og_image'] ?? null,
            ogType: $data['og_type'] ?? 'website',
            twitterCard: $data['twitter_card'] ?? 'summary_large_image',
            twitterTitle: $data['twitter_title'] ?? null,
            twitterDescription: $data['twitter_description'] ?? null,
            twitterImage: $data['twitter_image'] ?? null,
            canonicalUrl: $data['canonical_url'] ?? null,
            robots: $data['robots'] ?? null,
            noIndex: (bool) ($data['no_index'] ?? false),
            priority: (float) ($data['priority'] ?? 0.5),
            changefreq: $data['changefreq'] ?? 'monthly',
            schemaJsonLd: $data['schema_json_ld'] ?? null,
        );
    }

    /**
     * Convert the DTO to an associative array.
     */
    public function toArray(): array
    {
        return [
            'meta_title' => $this->metaTitle,
            'meta_description' => $this->metaDescription,
            'meta_keywords' => $this->metaKeywords,
            'og_title' => $this->ogTitle,
            'og_description' => $this->ogDescription,
            'og_image' => $this->ogImage,
            'og_type' => $this->ogType,
            'twitter_card' => $this->twitterCard,
            'twitter_title' => $this->twitterTitle,
            'twitter_description' => $this->twitterDescription,
            'twitter_image' => $this->twitterImage,
            'canonical_url' => $this->canonicalUrl,
            'robots' => $this->robots,
            'no_index' => $this->noIndex,
            'priority' => $this->priority,
            'changefreq' => $this->changefreq,
            'schema_json_ld' => $this->schemaJsonLd,
        ];
    }

    /**
     * Create a new instance with specific fields overridden.
     */
    public function withOverrides(array $overrides): self
    {
        $data = $this->toArray();

        foreach ($overrides as $key => $value) {
            if (array_key_exists($key, $data)) {
                $data[$key] = $value;
            }
        }

        return self::fromArray($data);
    }

    /**
     * Get the effective title, falling back from OG title to meta title.
     */
    public function getEffectiveTitle(): string
    {
        return $this->ogTitle ?? $this->metaTitle ?? '';
    }

    /**
     * Get the effective description, falling back from OG description to meta description.
     */
    public function getEffectiveDescription(): string
    {
        return $this->ogDescription ?? $this->metaDescription ?? '';
    }

    /**
     * Get the effective image URL, falling back from OG image to Twitter image.
     */
    public function getEffectiveImage(): ?string
    {
        return $this->ogImage ?? $this->twitterImage ?? null;
    }
}
