<?php

declare(strict_types=1);

namespace Highblossom\ContentBlocks\Blocks;

use Highblossom\ContentBlocks\Services\AbstractBlock;

/**
 * Divider block for separating content sections.
 */
final class DividerBlock extends AbstractBlock
{
    /**
     * Get the block type identifier.
     */
    public function getType(): string
    {
        return 'divider';
    }

    /**
     * Get the validation rules for this block.
     */
    public function getValidationRules(): array
    {
        return [
            'style' => 'required|in:line,dots,space',
            'size' => 'nullable|in:sm,md,lg',
        ];
    }

    /**
     * Get the default attributes for this block.
     */
    public function getDefaultAttributes(): array
    {
        return [
            'style' => 'line',
            'size' => 'md',
        ];
    }

    /**
     * Get the attribute type casts.
     */
    protected function getAttributeCasts(): array
    {
        return [
            'style' => 'string',
            'size' => 'string',
        ];
    }
}
