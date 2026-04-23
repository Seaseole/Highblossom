<?php

namespace Highblossom\ContentBlocks\Blocks;

use Highblossom\ContentBlocks\Services\AbstractBlock;

class QuoteBlock extends AbstractBlock
{
    public function getType(): string
    {
        return 'quote';
    }

    public function getValidationRules(): array
    {
        return [
            'content' => 'nullable|string',
            'author' => 'nullable|string',
            'cite' => 'nullable|string',
            'class' => 'nullable|string',
        ];
    }

    public function getDefaultAttributes(): array
    {
        return [
            'content' => '',
            'author' => '',
            'cite' => '',
            'class' => '',
        ];
    }

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
