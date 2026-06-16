<?php

declare(strict_types=1);

namespace Highblossom\ContentBlocks\Blocks;

use Highblossom\ContentBlocks\Services\AbstractBlock;
use Highblossom\ContentBlocks\Services\HtmlSanitizer;

/**
 * HTML block with sanitized raw HTML support.
 */
final class HtmlBlock extends AbstractBlock
{
    private HtmlSanitizer $sanitizer;

    /**
     * Create a new HTML block instance.
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
        return 'html';
    }

    /**
     * Get the validation rules for this block.
     */
    public function getValidationRules(): array
    {
        return [
            'content' => 'required|string',
        ];
    }

    /**
     * Get the default attributes for this block.
     */
    public function getDefaultAttributes(): array
    {
        return [
            'content' => '',
        ];
    }

    /**
     * Get the attribute type casts.
     */
    protected function getAttributeCasts(): array
    {
        return [
            'content' => 'string',
        ];
    }

    /**
     * Normalize attributes by sanitizing HTML content.
     */
    public function normalizeAttributes(array $attributes): array
    {
        $attributes = parent::normalizeAttributes($attributes);

        if (isset($attributes['content'])) {
            $attributes['content'] = $this->sanitizer->sanitize($attributes['content']);
        }

        return $attributes;
    }
}
