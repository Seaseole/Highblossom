<?php

namespace Highblossom\ContentBlocks\Services;

use Highblossom\ContentBlocks\Contracts\BlockInterface;
use Illuminate\Support\Collection;

class BlockRegistry
{
    protected Collection $blocks;

    public function __construct()
    {
        $this->blocks = collect();
    }

    /**
     * Register a block type.
     *
     * @param string $type
     * @param BlockInterface $block
     * @return self
     */
    public function register(string $type, BlockInterface $block): self
    {
        $this->blocks->put($type, $block);

        return $this;
    }

    /**
     * Get a block by type.
     *
     * @param string $type
     * @return BlockInterface|null
     */
    public function get(string $type): ?BlockInterface
    {
        return $this->blocks->get($type);
    }

    /**
     * Check if a block type is registered.
     *
     * @param string $type
     * @return bool
     */
    public function has(string $type): bool
    {
        return $this->blocks->has($type);
    }

    /**
     * Get all registered block types.
     *
     * @return Collection
     */
    public function all(): Collection
    {
        return $this->blocks;
    }

    /**
     * Get all registered block type names.
     *
     * @return array
     */
    public function types(): array
    {
        return $this->blocks->keys()->toArray();
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
        $block = $this->get($type);

        if (!$block) {
            return $this->renderUnknownBlock($type, $attributes);
        }

        return $block->render($attributes);
    }

    /**
     * Render an unknown block fallback.
     *
     * @param string $type
     * @param array $attributes
     * @return string
     */
    protected function renderUnknownBlock(string $type, array $attributes): string
    {
        return "<!-- Unknown block type: {$type} -->";
    }
}
