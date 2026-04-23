<?php

namespace Highblossom\ContentBlocks\Blocks;

use Highblossom\ContentBlocks\Services\AbstractBlock;

class ImageBlock extends AbstractBlock
{
    public function getType(): string
    {
        return 'image';
    }

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
