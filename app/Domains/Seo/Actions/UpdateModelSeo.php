<?php

declare(strict_types=1);

namespace App\Domains\Seo\Actions;

use App\Domains\Seo\Contracts\HasSeoInterface;

final readonly class UpdateModelSeo
{
    public function __construct() {}

    public function execute(HasSeoInterface $model, array $data): void
    {
        $sanitized = $this->sanitizeInput($data);

        $model->updateSeo($sanitized);
    }

    private function sanitizeInput(array $data): array
    {
        $allowedFields = [
            'meta_title',
            'meta_description',
            'meta_keywords',
            'og_title',
            'og_description',
            'og_image',
            'og_type',
            'twitter_card',
            'twitter_title',
            'twitter_description',
            'twitter_image',
            'canonical_url',
            'robots',
            'no_index',
            'priority',
            'changefreq',
            'schema_json_ld',
        ];

        $sanitized = [];

        foreach ($allowedFields as $field) {
            if (array_key_exists($field, $data)) {
                $value = $data[$field];

                // Sanitize based on field type
                $sanitized[$field] = match ($field) {
                    'meta_title', 'og_title', 'twitter_title' => $this->sanitizeText($value, 70),
                    'meta_description', 'og_description', 'twitter_description' => $this->sanitizeText($value, 300),
                    'meta_keywords', 'og_type', 'twitter_card', 'changefreq', 'robots' => $this->sanitizeText($value, 255),
                    'canonical_url', 'og_image', 'twitter_image' => $this->sanitizeUrl($value),
                    'no_index' => (bool) $value,
                    'priority' => $this->sanitizePriority($value),
                    'schema_json_ld' => is_array($value) ? $value : null,
                    default => $this->sanitizeText($value, 255),
                };
            }
        }

        return array_filter($sanitized, fn ($v) => $v !== null && $v !== '');
    }

    private function sanitizeText(mixed $value, int $maxLength): ?string
    {
        if (! is_string($value)) {
            return null;
        }

        $cleaned = strip_tags($value);
        $cleaned = htmlspecialchars($cleaned, ENT_QUOTES, 'UTF-8');

        return mb_substr($cleaned, 0, $maxLength);
    }

    private function sanitizeUrl(mixed $value): ?string
    {
        if (! is_string($value) || $value === '') {
            return null;
        }

        $url = filter_var($value, FILTER_SANITIZE_URL);

        return filter_var($url, FILTER_VALIDATE_URL) !== false ? $url : null;
    }

    private function sanitizePriority(mixed $value): float
    {
        $float = is_numeric($value) ? (float) $value : 0.5;

        return max(0.0, min(1.0, $float));
    }
}
