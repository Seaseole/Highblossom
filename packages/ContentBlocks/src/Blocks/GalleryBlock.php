<?php

namespace Highblossom\ContentBlocks\Blocks;

use Highblossom\ContentBlocks\Services\AbstractBlock;

final class GalleryBlock extends AbstractBlock
{
    public function getType(): string
    {
        return 'gallery';
    }

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

    public function getDefaultAttributes(): array
    {
        return [
            'images' => [],
            'columns' => 3,
        ];
    }

    protected function getAttributeCasts(): array
    {
        return [
            'images' => 'array',
            'columns' => 'int',
        ];
    }
}
