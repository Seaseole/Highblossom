<?php

declare(strict_types=1);

namespace Highblossom\ContentBlocks\Blocks;

use Highblossom\ContentBlocks\Services\AbstractBlock;

/**
 * Accordion block with expandable/collapsible items.
 */
final class AccordionBlock extends AbstractBlock
{
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
            'items.*.title' => 'required|string',
            'items.*.content' => 'required|string',
            'multiple_open' => 'required|boolean',
        ];
    }

    /**
     * Get the default attributes for this block.
     */
    public function getDefaultAttributes(): array
    {
        return [
            'items' => [],
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
}
