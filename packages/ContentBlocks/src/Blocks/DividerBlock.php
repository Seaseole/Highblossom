<?php

namespace Highblossom\ContentBlocks\Blocks;

use Highblossom\ContentBlocks\Services\AbstractBlock;

final class DividerBlock extends AbstractBlock
{
    public function getType(): string
    {
        return 'divider';
    }

    public function getValidationRules(): array
    {
        return [
            'style' => 'required|in:line,dots,space',
            'size' => 'nullable|in:sm,md,lg',
        ];
    }

    public function getDefaultAttributes(): array
    {
        return [
            'style' => 'line',
            'size' => 'md',
        ];
    }

    protected function getAttributeCasts(): array
    {
        return [
            'style' => 'string',
            'size' => 'string',
        ];
    }
}
