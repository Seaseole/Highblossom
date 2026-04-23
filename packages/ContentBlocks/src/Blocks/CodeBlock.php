<?php

namespace Highblossom\ContentBlocks\Blocks;

use Highblossom\ContentBlocks\Services\AbstractBlock;

class CodeBlock extends AbstractBlock
{
    public function getType(): string
    {
        return 'code';
    }

    public function getValidationRules(): array
    {
        return [
            'content' => 'nullable|string',
            'language' => 'nullable|string',
            'class' => 'nullable|string',
        ];
    }

    public function getDefaultAttributes(): array
    {
        return [
            'content' => '',
            'language' => '',
            'class' => '',
        ];
    }

    protected function getAttributeCasts(): array
    {
        return [
            'content' => 'string',
            'language' => 'string',
            'class' => 'string',
        ];
    }
}
