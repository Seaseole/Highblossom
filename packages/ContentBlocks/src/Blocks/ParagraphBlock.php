<?php

declare(strict_types=1);

namespace Highblossom\ContentBlocks\Blocks;

use Highblossom\ContentBlocks\Services\AbstractBlock;
use Highblossom\ContentBlocks\Services\HtmlSanitizer;

/**
 * Paragraph block for text content.
 */
class ParagraphBlock extends AbstractBlock
{
    private HtmlSanitizer $sanitizer;

    /**
     * Create a new paragraph block instance.
     */
    public function __construct(HtmlSanitizer $sanitizer)
    {
        $this->sanitizer = $sanitizer;
    }

    /**
     * Get the block type identifier.
     */
    public function getType(): string
    {
        return 'paragraph';
    }

    /**
     * Get the validation rules for this block.
     */
    public function getValidationRules(): array
    {
        return [
            'content' => 'nullable|string|max:60000',
            'class' => 'nullable|string|max:255',
        ];
    }

    /**
     * Get the default attributes for this block.
     */
    public function getDefaultAttributes(): array
    {
        return [
            'content' => '',
            'class' => '',
        ];
    }

    /**
     * Get the attribute type casts.
     */
    protected function getAttributeCasts(): array
    {
        return [
            'content' => 'string',
            'class' => 'string',
        ];
    }

    /**
     * Normalize attributes by sanitizing the rich text content.
     */
    public function normalizeAttributes(array $attributes): array
    {
        $attributes = parent::normalizeAttributes($attributes);

        if (! empty($attributes['content'])) {
            $attributes['content'] = $this->sanitizer->sanitize((string) $attributes['content']);
        }

        return $attributes;
    }
}
