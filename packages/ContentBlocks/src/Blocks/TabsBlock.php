<?php

declare(strict_types=1);

namespace Highblossom\ContentBlocks\Blocks;

use Highblossom\ContentBlocks\Services\AbstractBlock;
use Highblossom\ContentBlocks\Services\BlockRenderer;

/**
 * Tabs block with labeled tabbed content.
 */
final class TabsBlock extends AbstractBlock
{
    private BlockRenderer $blockRenderer;

    /**
     * Create a new tabs block instance.
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
        return 'tabs';
    }

    /**
     * Get the validation rules for this block.
     */
    public function getValidationRules(): array
    {
        return [
            'tabs' => 'required|array|min:1',
            'tabs.*.label' => 'required|string',
            'tabs.*.content' => 'required|array',
        ];
    }

    /**
     * Get the default attributes for this block.
     */
    public function getDefaultAttributes(): array
    {
        return [
            'tabs' => [
                ['label' => 'Tab 1', 'content' => []],
                ['label' => 'Tab 2', 'content' => []],
            ],
        ];
    }

    /**
     * Get the attribute type casts.
     */
    protected function getAttributeCasts(): array
    {
        return [
            'tabs' => 'array',
        ];
    }

    /**
     * Build the tabs view with tab data.
     */
    public function buildView(array $attributes): string
    {
        $tabs = $attributes['tabs'] ?? [];

        return view('content-blocks::tabs', [
            'tabs' => $tabs,
            'blockRenderer' => $this->blockRenderer,
        ])->render();
    }
}
