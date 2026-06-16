<?php

declare(strict_types=1);

namespace Highblossom\ContentBlocks\Blocks;

use Highblossom\ContentBlocks\Services\AbstractBlock;

/**
 * Call-to-action block with title, description, and button.
 */
class CTABlock extends AbstractBlock
{
    /**
     * Get the block type identifier.
     */
    public function getType(): string
    {
        return 'cta';
    }

    /**
     * Get the validation rules for this block.
     */
    public function getValidationRules(): array
    {
        return [
            'title' => 'nullable|string',
            'description' => 'nullable|string',
            'button_text' => 'nullable|string',
            'button_url' => 'nullable|string',
            'class' => 'nullable|string',
        ];
    }

    /**
     * Get the default attributes for this block.
     */
    public function getDefaultAttributes(): array
    {
        return [
            'title' => '',
            'description' => '',
            'button_text' => '',
            'button_url' => '',
            'class' => '',
        ];
    }

    /**
     * Get the attribute type casts.
     */
    protected function getAttributeCasts(): array
    {
        return [
            'title' => 'string',
            'description' => 'string',
            'button_text' => 'string',
            'button_url' => 'string',
            'class' => 'string',
        ];
    }

    /**
     * Get the view name for this block.
     */
    protected function getViewName(): string
    {
        return 'cta';
    }
}
