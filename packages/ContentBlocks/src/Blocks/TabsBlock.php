<?php

namespace Highblossom\ContentBlocks\Blocks;

use Highblossom\ContentBlocks\Services\AbstractBlock;
use Highblossom\ContentBlocks\Services\BlockRenderer;

final class TabsBlock extends AbstractBlock
{
    private BlockRenderer $blockRenderer;

    public function __construct(BlockRenderer $blockRenderer)
    {
        $this->blockRenderer = $blockRenderer;
    }

    public function getType(): string
    {
        return 'tabs';
    }

    public function getValidationRules(): array
    {
        return [
            'tabs' => 'required|array|min:1',
            'tabs.*.label' => 'required|string',
            'tabs.*.content' => 'required|array',
        ];
    }

    public function getDefaultAttributes(): array
    {
        return [
            'tabs' => [
                ['label' => 'Tab 1', 'content' => []],
                ['label' => 'Tab 2', 'content' => []],
            ],
        ];
    }

    protected function getAttributeCasts(): array
    {
        return [
            'tabs' => 'array',
        ];
    }

    public function buildView(array $attributes): string
    {
        $tabs = $attributes['tabs'] ?? [];

        return view('content-blocks::tabs', [
            'tabs' => $tabs,
            'blockRenderer' => $this->blockRenderer,
        ])->render();
    }
}
