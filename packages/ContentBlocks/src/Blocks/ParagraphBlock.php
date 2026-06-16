<?php

declare(strict_types=1);

namespace Highblossom\ContentBlocks\Blocks;

use Highblossom\ContentBlocks\Services\AbstractBlock;

/**
 * Paragraph block for text content.
 */
class ParagraphBlock extends AbstractBlock
{
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
            'content' => 'nullable|string',
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
}
