<?php

declare(strict_types=1);

namespace App\Domains\Seo\DataTransferObjects;

use InvalidArgumentException;

final readonly class SeoMetadata
{
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

    public function getEffectiveTitle(): string
    {
        return $this->ogTitle ?? $this->metaTitle ?? '';
    }

    public function getEffectiveDescription(): string
    {
        return $this->ogDescription ?? $this->metaDescription ?? '';
    }

    public function getEffectiveImage(): ?string
    {
        return $this->ogImage ?? $this->twitterImage ?? null;
    }
}
