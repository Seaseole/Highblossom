<?php

namespace Highblossom\ContentBlocks\Blocks;

use Highblossom\ContentBlocks\Services\AbstractBlock;

final class TableBlock extends AbstractBlock
{
    public function getType(): string
    {
        return 'table';
    }

    public function getValidationRules(): array
    {
        return [
            'headers' => 'required|array|min:1',
            'headers.*' => 'required|string',
            'rows' => 'required|array',
            'rows.*' => 'array',
            'rows.*.*' => 'required|string',
            'caption' => 'nullable|string',
        ];
    }

    public function getDefaultAttributes(): array
    {
        return [
            'headers' => ['Column 1', 'Column 2'],
            'rows' => [['Row 1 Cell 1', 'Row 1 Cell 2']],
            'caption' => null,
        ];
    }

    protected function getAttributeCasts(): array
    {
        return [
            'headers' => 'array',
            'rows' => 'array',
            'caption' => 'string',
        ];
    }
}
