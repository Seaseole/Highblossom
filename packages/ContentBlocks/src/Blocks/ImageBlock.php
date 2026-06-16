<?php

declare(strict_types=1);

namespace Highblossom\ContentBlocks\Blocks;

use Highblossom\ContentBlocks\Services\AbstractBlock;

/**
 * Image block with src, alt, caption and dimensions support.
 */
class ImageBlock extends AbstractBlock
{
    /**
     * Get the block type identifier.
     */
    public function getType(): string
    {
        return 'image';
    }

    /**
     * Get the validation rules for this block.
     */
    public function getValidationRules(): array
    {
        return [
            'src' => 'nullable|string',
            'alt' => 'nullable|string',
            'caption' => 'nullable|string',
            'width' => 'nullable|integer',
            'height' => 'nullable|integer',
            'class' => 'nullable|string',
        ];
    }

    /**
     * Get the default attributes for this block.
     */
    public function getDefaultAttributes(): array
    {
        return [
            'src' => '',
            'alt' => '',
            'caption' => '',
            'width' => null,
            'height' => null,
            'class' => '',
        ];
    }

    /**
     * Get the attribute type casts.
     */
    protected function getAttributeCasts(): array
    {
        return [
            'src' => 'string',
            'alt' => 'string',
            'caption' => 'string',
            'width' => 'integer',
            'height' => 'integer',
            'class' => 'string',
        ];
    }
}
