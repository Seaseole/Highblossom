<?php

declare(strict_types=1);

namespace Highblossom\ContentBlocks\Blocks;

use Highblossom\ContentBlocks\Services\AbstractBlock;

/**
 * Alert block for displaying contextual messages.
 */
final class AlertBlock extends AbstractBlock
{
    /**
     * Get the block type identifier.
     */
    public function getType(): string
    {
        return 'alert';
    }

    /**
     * Get the validation rules for this block.
     */
    public function getValidationRules(): array
    {
        return [
            'type' => 'required|in:info,success,warning,danger',
            'title' => 'nullable|string',
            'content' => 'required|string',
            'dismissible' => 'required|boolean',
        ];
    }

    /**
     * Get the default attributes for this block.
     */
    public function getDefaultAttributes(): array
    {
        return [
            'type' => 'info',
            'title' => null,
            'content' => '',
            'dismissible' => false,
        ];
    }

    /**
     * Get the attribute type casts.
     */
    protected function getAttributeCasts(): array
    {
        return [
            'type' => 'string',
            'title' => 'string',
            'content' => 'string',
            'dismissible' => 'bool',
        ];
    }
}
