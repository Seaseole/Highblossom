<?php

namespace Highblossom\ContentBlocks\Blocks;

use Highblossom\ContentBlocks\Services\AbstractBlock;
use Highblossom\ContentBlocks\Services\BlockRenderer;

final class ColumnsBlock extends AbstractBlock
{
    private BlockRenderer $blockRenderer;

    public function __construct(BlockRenderer $blockRenderer)
    {
        $this->blockRenderer = $blockRenderer;
    }

    public function getType(): string
    {
        return 'columns';
    }

    public function getValidationRules(): array
    {
        return [
            'columns' => 'required|array|min:1',
            'column_widths' => 'required|array',
            'column_widths.*' => 'required|integer|min:1|max:12',
        ];
    }

    public function getDefaultAttributes(): array
    {
        return [
            'columns' => [[], []],
            'column_widths' => [6, 6],
        ];
    }

    protected function getAttributeCasts(): array
    {
        return [
            'columns' => 'array',
            'column_widths' => 'array',
        ];
    }

    public function buildView(array $attributes): string
    {
        $columns = $attributes['columns'] ?? [];
        $columnWidths = $attributes['column_widths'] ?? [];

        return view('content-blocks::columns', [
            'columns' => $columns,
            'columnWidths' => $columnWidths,
            'blockRenderer' => $this->blockRenderer,
        ])->render();
    }
}
