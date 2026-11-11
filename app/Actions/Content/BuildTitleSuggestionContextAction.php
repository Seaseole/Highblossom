<?php

declare(strict_types=1);

namespace App\Actions\Content;

use App\Concerns\PreparesAiPromptText;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Services\Ai\DataTransferObjects\TitleSuggestionContext;

/**
 * Turn the post editor's current field values into a bounded, redacted brief.
 *
 * The client only sends field values and selected IDs. Everything that leaves
 * the server is resolved here, so a crafted request cannot smuggle extra prose,
 * attach arbitrary taxonomy labels, or exceed the prompt budget. Post::plainText()
 * is reused so the block flattening stays identical to the read-time estimate.
 */
final class BuildTitleSuggestionContextAction
{
    use PreparesAiPromptText;

    /**
     * Build the context from validated request data.
     *
     * @param array<string, mixed> $data Validated title suggestion payload.
     *
     * @return TitleSuggestionContext The bounded brief.
     */
    public function __invoke(array $data): TitleSuggestionContext
    {
        $categoryIds = $this->ids($data['categories'] ?? []);
        $tagIds = $this->ids($data['tags'] ?? []);

        $categories = $categoryIds === []
            ? []
            : Category::query()->whereIn('id', $categoryIds)->pluck('name')->all();

        $tags = $tagIds === []
            ? []
            : Tag::query()->whereIn('id', $tagIds)->pluck('name')->all();

        $recentTitles = Post::query()
            ->latest('id')
            ->limit(10)
            ->pluck('title')
            ->filter(fn ($title): bool => is_string($title) && $title !== '')
            ->values()
            ->all();

        return new TitleSuggestionContext(
            workingTitle: $this->clean((string) ($data['title'] ?? '')),
            excerpt: $this->clean((string) ($data['excerpt'] ?? '')),
            body: $this->body($data['content'] ?? null),
            categories: array_values(array_map('strval', $categories)),
            tags: array_values(array_map('strval', $tags)),
            siteName: $this->clean((string) config('seo.site_name', config('app.name'))),
            locale: app()->getLocale(),
            recentTitles: array_values(array_map([$this, 'clean'], $recentTitles)),
        );
    }

    /**
     * Keep only numeric IDs so a malformed array cannot reach a query.
     *
     * @param mixed $value The submitted array.
     *
     * @return list<int>
     */
    private function ids(mixed $value): array
    {
        if (! is_array($value)) {
            return [];
        }

        return array_values(array_map(
            'intval',
            array_filter($value, fn ($id): bool => is_numeric($id))
        ));
    }
}
