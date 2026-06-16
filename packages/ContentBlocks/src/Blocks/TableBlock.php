<?php

declare(strict_types=1);

namespace Highblossom\ContentBlocks\Blocks;

use Highblossom\ContentBlocks\Services\AbstractBlock;

/**
 * Table block with headers, rows, and caption support.
 */
final class TableBlock extends AbstractBlock
{
    /**
     * Get the block type identifier.
     */
    public function getType(): string
    {
        return 'table';
    }

    /**
     * Get the validation rules for this block.
     */
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

    /**
     * Get the default attributes for this block.
     */
    public function getDefaultAttributes(): array
    {
        return [
            'headers' => ['Column 1', 'Column 2'],
            'rows' => [['Row 1 Cell 1', 'Row 1 Cell 2']],
            'caption' => null,
        ];
    }

    /**
     * Get the attribute type casts.
     */
    protected function getAttributeCasts(): array
    {
        return [
            'headers' => 'array',
            'rows' => 'array',
            'caption' => 'string',
        ];
    }
}
