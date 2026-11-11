<?php

declare(strict_types=1);

namespace App\Actions\Content;

use App\Concerns\PreparesAiPromptText;
use App\Services\Ai\DataTransferObjects\CopySuggestionContext;

/**
 * Turn a block editor's current field values into a bounded, redacted brief
 * for block copy suggestions.
 *
 * The client only sends the block type, the target field and field values; the
 * copy kind and placement are resolved here from a fixed map, and the draft
 * prose is flattened from the block JSON with the same Post::plainText() path
 * the title and excerpt briefs use.
 */
final class BuildBlockSuggestionContextAction
{
    use PreparesAiPromptText;

    /**
     * The copyable fields per block type, mapped to their copy kind and the
     * placement line shown to the model. The request validates against this
     * same map, so the two cannot drift.
     *
     * @var array<string, array<string, array{0: string, 1: string}>>
     */
    public const FIELD_KINDS = [
        'paragraph' => [
            'content' => ['block_paragraph', 'page paragraph'],
        ],
        'heading' => [
            'content' => ['block_heading', 'section heading'],
        ],
        'cta' => [
            'title' => ['block_heading', 'call-to-action title'],
            'description' => ['block_text', 'call-to-action description'],
            'button_text' => ['block_button', 'call-to-action button'],
        ],
        'alert' => [
            'title' => ['block_heading', 'alert title'],
            'content' => ['block_text', 'alert message'],
        ],
    ];

    /**
     * Build the context from validated request data.
     *
     * @param array<string, mixed> $data Validated block suggestion payload.
     *
     * @return CopySuggestionContext The bounded brief.
     */
    public function __invoke(array $data): CopySuggestionContext
    {
        [$kind, $placement] = self::FIELD_KINDS[$data['block_type']][$data['field']];

        return new CopySuggestionContext(
            kind: $kind,
            subject: $this->clean((string) ($data['title'] ?? '')),
            body: $this->body($data['content'] ?? null),
            current: $this->clean((string) ($data['current'] ?? '')),
            keywords: '',
            siteName: $this->clean((string) config('seo.site_name', config('app.name'))),
            locale: app()->getLocale(),
            placement: $placement,
            tone: (string) ($data['tone'] ?? ''),
        );
    }
}
