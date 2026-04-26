<?php

namespace Highblossom\ContentBlocks\Blocks;

use Highblossom\ContentBlocks\Services\AbstractBlock;
use Highblossom\ContentBlocks\Services\BlockRenderer;

final class CarouselBlock extends AbstractBlock
{
    private BlockRenderer $blockRenderer;

    public function __construct(BlockRenderer $blockRenderer)
    {
        $this->blockRenderer = $blockRenderer;
    }

    public function getType(): string
    {
        return 'carousel';
    }

    public function getValidationRules(): array
    {
        return [
            'slides' => 'required|array|min:1',
            'autoplay' => 'required|boolean',
            'interval' => 'required|integer|min:1|max:60',
        ];
    }

    public function getDefaultAttributes(): array
    {
        return [
            'slides' => [],
            'autoplay' => false,
            'interval' => 5,
        ];
    }

    protected function getAttributeCasts(): array
    {
        return [
            'slides' => 'array',
            'autoplay' => 'bool',
            'interval' => 'int',
        ];
    }

    public function buildView(array $attributes): string
    {
        $slides = $attributes['slides'] ?? [];
        $autoplay = $attributes['autoplay'] ?? false;
        $interval = $attributes['interval'] ?? 5;

        return view('content-blocks::carousel', [
            'slides' => $slides,
            'autoplay' => $autoplay,
            'interval' => $interval,
            'blockRenderer' => $this->blockRenderer,
        ])->render();
    }
}
