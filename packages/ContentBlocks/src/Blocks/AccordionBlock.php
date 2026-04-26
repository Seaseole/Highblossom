<?php

namespace Highblossom\ContentBlocks\Blocks;

use Highblossom\ContentBlocks\Services\AbstractBlock;

final class AccordionBlock extends AbstractBlock
{
    public function getType(): string
    {
        return 'accordion';
    }

    public function getValidationRules(): array
    {
        return [
            'items' => 'required|array|min:1',
            'items.*.title' => 'required|string',
            'items.*.content' => 'required|string',
            'multiple_open' => 'required|boolean',
        ];
    }

    public function getDefaultAttributes(): array
    {
        return [
            'items' => [],
            'multiple_open' => false,
        ];
    }

    protected function getAttributeCasts(): array
    {
        return [
            'items' => 'array',
            'multiple_open' => 'bool',
        ];
    }
}
