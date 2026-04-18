<?php

declare(strict_types=1);

namespace App\Domains\Seo\Actions;

use App\Domains\Seo\DataTransferObjects\SeoMetadata;
use Illuminate\Support\Facades\AI;

final readonly class GenerateSeoSuggestions
{
    public function __construct() {}

    public function execute(
        string $title,
        ?string $description = null,
        ?string $content = null,
    ): SeoMetadata {
        $prompt = $this->buildPrompt($title, $description, $content);

        $response = AI::text()->generate([
            'model' => 'gpt-4o-mini',
            'prompt' => $prompt,
            'temperature' => 0.7,
            'max_tokens' => 500,
        ]);

        $suggestions = $this->parseResponse($response->text);

        return new SeoMetadata(
            metaTitle: $suggestions['meta_title'] ?? null,
            metaDescription: $suggestions['meta_description'] ?? null,
            metaKeywords: $suggestions['meta_keywords'] ?? null,
            ogTitle: $suggestions['og_title'] ?? null,
            ogDescription: $suggestions['og_description'] ?? null,
            twitterTitle: $suggestions['twitter_title'] ?? null,
            twitterDescription: $suggestions['twitter_description'] ?? null,
        );
    }

    private function buildPrompt(string $title, ?string $description, ?string $content): string
    {
        $context = "Title: {$title}\n";

        if ($description !== null && $description !== '') {
            $context .= "Short Description: {$description}\n";
        }

        if ($content !== null && $content !== '') {
            $truncatedContent = substr(strip_tags($content), 0, 1000);
            $context .= "Content: {$truncatedContent}\n";
        }

        return <<<PROMPT
        Generate SEO-optimized metadata for the following content. Return ONLY a JSON object with these exact keys:
        - meta_title: 50-60 characters, compelling, include main keyword
        - meta_description: 150-160 characters, action-oriented, include CTA
        - meta_keywords: 5-8 relevant keywords comma-separated
        - og_title: OpenGraph title (slightly longer, more engaging)
        - og_description: OpenGraph description (longer, more descriptive)
        - twitter_title: Twitter card title
        - twitter_description: Twitter card description

        Content to analyze:
        {$context}

        Return valid JSON only, no markdown formatting.
        PROMPT;
    }

    private function parseResponse(string $response): array
    {
        // Extract JSON from response (in case there's any extra text)
        $json = $response;

        // Try to find JSON between code blocks
        if (preg_match('/```json\s*(.*?)\s*```/s', $response, $matches)) {
            $json = $matches[1];
        } elseif (preg_match('/```\s*(.*?)\s*```/s', $response, $matches)) {
            $json = $matches[1];
        }

        $decoded = json_decode($json, true);

        if (! is_array($decoded)) {
            return [];
        }

        return $decoded;
    }
}
