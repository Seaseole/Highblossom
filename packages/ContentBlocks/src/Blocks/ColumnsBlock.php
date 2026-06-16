<?php

declare(strict_types=1);

namespace Highblossom\ContentBlocks\Blocks;

use Highblossom\ContentBlocks\Services\AbstractBlock;
use Highblossom\ContentBlocks\Services\BlockRenderer;

/**
 * Columns block for multi-column layouts.
 */
final class ColumnsBlock extends AbstractBlock
{
    private BlockRenderer $blockRenderer;

    /**
     * Create a new columns block instance.
     */
    public function __construct(BlockRenderer $blockRenderer)
    {
        $this->blockRenderer = $blockRenderer;
    }

    /**
     * Get the block type identifier.
     */
    public function getType(): string
    {
        return 'columns';
    }

    /**
     * Get the validation rules for this block.
     */
    public function getValidationRules(): array
    {
        return [
            'columns' => 'required|array|min:1',
            'column_widths' => 'required|array',
            'column_widths.*' => 'required|integer|min:1|max:12',
        ];
    }

    /**
     * Get the default attributes for this block.
     */
    public function getDefaultAttributes(): array
    {
        return [
            'columns' => [[], []],
            'column_widths' => [6, 6],
        ];
    }

    /**
     * Get the attribute type casts.
     */
    protected function getAttributeCasts(): array
    {
        return [
            'columns' => 'array',
            'column_widths' => 'array',
        ];
    }

    /**
     * Build the columns view with column data.
     */
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
