<?php

declare(strict_types=1);

namespace Highblossom\ContentBlocks\Blocks;

use Highblossom\ContentBlocks\Services\AbstractBlock;

/**
 * Gallery block for displaying image collections.
 */
final class GalleryBlock extends AbstractBlock
{
    /**
     * Get the block type identifier.
     */
    public function getType(): string
    {
        return 'gallery';
    }

    /**
     * Get the validation rules for this block.
     */
    public function getValidationRules(): array
    {
        return [
            'images' => 'required|array|min:1',
            'images.*.src' => 'required|string',
            'images.*.alt' => 'required|string',
            'images.*.caption' => 'nullable|string',
            'columns' => 'required|integer|min:1|max:6',
        ];
    }

    /**
     * Get the default attributes for this block.
     */
    public function getDefaultAttributes(): array
    {
        return [
            'images' => [],
            'columns' => 3,
        ];
    }

    /**
     * Get the attribute type casts.
     */
    protected function getAttributeCasts(): array
    {
        return [
            'images' => 'array',
            'columns' => 'int',
        ];
    }
}
