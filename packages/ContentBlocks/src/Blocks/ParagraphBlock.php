<?php

namespace Highblossom\ContentBlocks\Blocks;

use Highblossom\ContentBlocks\Services\AbstractBlock;

class ParagraphBlock extends AbstractBlock
{
    public function getType(): string
    {
        return 'paragraph';
    }

    public function getValidationRules(): array
    {
        return [
            'content' => 'nullable|string',
            'class' => 'nullable|string',
        ];
    }

    public function getDefaultAttributes(): array
    {
        return [
            'content' => '',
            'class' => '',
        ];
    }

    protected function getAttributeCasts(): array
    {
        return [
            'content' => 'string',
            'class' => 'string',
        ];
    }
}
