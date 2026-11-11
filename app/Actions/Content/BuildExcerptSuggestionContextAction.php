<?php

declare(strict_types=1);

namespace App\Actions\Content;

use App\Concerns\PreparesAiPromptText;
use App\Services\Ai\DataTransferObjects\CopySuggestionContext;

/**
 * Turn the post editor's current field values into a bounded, redacted brief
 * for excerpt suggestions.
 *
 * The client only sends field values; the body is flattened from the block
 * JSON with the same Post::plainText() path the title brief uses, so the two
 * features cannot drift on what prose is allowed to leave the server.
 */
final class BuildExcerptSuggestionContextAction
{
    use PreparesAiPromptText;

    /**
     * Build the context from validated request data.
     *
     * @param array<string, mixed> $data Validated excerpt suggestion payload.
     *
     * @return CopySuggestionContext The bounded brief.
     */
    public function __invoke(array $data): CopySuggestionContext
    {
        return new CopySuggestionContext(
            kind: 'excerpt',
            subject: $this->clean((string) ($data['title'] ?? '')),
            body: $this->body($data['content'] ?? null),
            current: $this->clean((string) ($data['excerpt'] ?? '')),
            keywords: '',
            siteName: $this->clean((string) config('seo.site_name', config('app.name'))),
            locale: app()->getLocale(),
            tone: (string) ($data['tone'] ?? ''),
        );
    }
}
