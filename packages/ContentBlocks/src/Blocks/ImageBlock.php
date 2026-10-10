<?php

declare(strict_types=1);

namespace Highblossom\ContentBlocks\Blocks;

use Highblossom\ContentBlocks\Services\AbstractBlock;

/**
 * Image block: one image, or an ordered set of images rendered as a gallery.
 */
class ImageBlock extends AbstractBlock
{
    /**
     * Upper bound on the image set, keeping a post's block payload bounded.
     */
    public const MAX_IMAGES = 24;

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
            'images' => 'nullable|array|max:'.self::MAX_IMAGES,
            'images.*.src' => 'required|string',
            'images.*.alt' => 'nullable|string',
            'images.*.caption' => 'nullable|string',
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
            'images' => [],
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
            'images' => 'array',
        ];
    }
}
