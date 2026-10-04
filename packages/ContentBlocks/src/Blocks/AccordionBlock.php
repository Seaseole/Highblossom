<?php

declare(strict_types=1);

namespace Highblossom\ContentBlocks\Blocks;

use Highblossom\ContentBlocks\Services\AbstractBlock;
use Highblossom\ContentBlocks\Services\HtmlSanitizer;

/**
 * Accordion block with expandable/collapsible items.
 */
final class AccordionBlock extends AbstractBlock
{
    private HtmlSanitizer $sanitizer;

    /**
     * Create a new accordion block instance.
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
        return 'accordion';
    }

    /**
     * Get the validation rules for this block.
     */
    public function getValidationRules(): array
    {
        return [
            'items' => 'required|array|min:1',
            'items.*.title' => 'required|string|max:255',
            'items.*.content' => 'required|string|max:60000',
            'multiple_open' => 'required|boolean',
        ];
    }

    /**
     * Get the default attributes for this block.
     */
    public function getDefaultAttributes(): array
    {
        return [
            'items' => [
                ['title' => '', 'content' => ''],
            ],
            'multiple_open' => false,
        ];
    }

    /**
     * Get the attribute type casts.
     */
    protected function getAttributeCasts(): array
    {
        return [
            'items' => 'array',
            'multiple_open' => 'bool',
        ];
    }

    /**
     * Normalize attributes by sanitizing each item's rich text content.
     */
    public function normalizeAttributes(array $attributes): array
    {
        $attributes = parent::normalizeAttributes($attributes);

        foreach ($attributes['items'] as $index => $item) {
            if (is_array($item) && isset($item['content']) && is_string($item['content']) && $item['content'] !== '') {
                $item['content'] = $this->sanitizer->sanitize($item['content']);
                $attributes['items'][$index] = $item;
            }
        }

        return $attributes;
    }
}
