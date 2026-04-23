<?php

namespace Highblossom\ContentBlocks\Services;

class BlockRenderer
{
    protected BlockRegistry $registry;
    protected int $maxDepth = 10;
    protected int $currentDepth = 0;

    public function __construct(BlockRegistry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * Render a block by type with attributes.
     *
     * @param string $type
     * @param array $attributes
     * @return string
     */
    public function render(string $type, array $attributes = []): string
    {
        return $this->registry->render($type, $attributes);
    }

    /**
     * Render multiple blocks.
     *
     * @param array $blocks
     * @return string
     */
    public function renderMany(array $blocks): string
    {
        return collect($blocks)
            ->map(fn ($block) => $this->render($block['type'], $block['attributes'] ?? []))
            ->implode('');
    }

    /**
     * Render a block with depth tracking for nested blocks.
     *
     * @param string $type
     * @param array $attributes
     * @return string
     */
    public function renderWithDepth(string $type, array $attributes = []): string
    {
        if ($this->currentDepth >= $this->maxDepth) {
            return "<!-- Max nesting depth reached for {$type} block -->";
        }

        $this->currentDepth++;
        $result = $this->render($type, $attributes);
        $this->currentDepth--;

        return $result;
    }

    /**
     * Set the maximum nesting depth.
     *
     * @param int $depth
     * @return self
     */
    public function setMaxDepth(int $depth): self
    {
        $this->maxDepth = $depth;
        return $this;
    }
}
