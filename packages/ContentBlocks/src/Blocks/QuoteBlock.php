<?php

declare(strict_types=1);

namespace Highblossom\ContentBlocks\Blocks;

use Highblossom\ContentBlocks\Services\AbstractBlock;

/**
 * Quote block with author and citation support.
 */
class QuoteBlock extends AbstractBlock
{
    /**
     * Get the block type identifier.
     */
    public function getType(): string
    {
        return 'quote';
    }

    /**
     * Get the validation rules for this block.
     */
    public function getValidationRules(): array
    {
        return [
            'content' => 'nullable|string',
            'author' => 'nullable|string',
            'cite' => 'nullable|string',
            'class' => 'nullable|string',
        ];
    }

    /**
     * Get the default attributes for this block.
     */
    public function getDefaultAttributes(): array
    {
        return [
            'content' => '',
            'author' => '',
            'cite' => '',
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
            'author' => 'string',
            'cite' => 'string',
            'class' => 'string',
        ];
    }
}
