<?php

declare(strict_types=1);

namespace Highblossom\ContentBlocks\Blocks;

use Highblossom\ContentBlocks\Services\AbstractBlock;
use Highblossom\ContentBlocks\Services\BlockRenderer;

/**
 * Carousel block with slides and autoplay support.
 */
final class CarouselBlock extends AbstractBlock
{
    private BlockRenderer $blockRenderer;

    /**
     * Create a new carousel block instance.
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
        return 'carousel';
    }

    /**
     * Get the validation rules for this block.
     */
    public function getValidationRules(): array
    {
        return [
            'slides' => 'required|array|min:1',
            'autoplay' => 'required|boolean',
            'interval' => 'required|integer|min:1|max:60',
        ];
    }

    /**
     * Get the default attributes for this block.
     */
    public function getDefaultAttributes(): array
    {
        return [
            'slides' => [],
            'autoplay' => false,
            'interval' => 5,
        ];
    }

    /**
     * Get the attribute type casts.
     */
    protected function getAttributeCasts(): array
    {
        return [
            'slides' => 'array',
            'autoplay' => 'bool',
            'interval' => 'int',
        ];
    }

    /**
     * Build the carousel view with slides.
     */
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
