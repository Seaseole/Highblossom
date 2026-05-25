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
     */
    public function register(string $type, BlockInterface $block): self
    {
        $this->blocks->put($type, $block);

        return $this;
    }

    /**
     * Get a block by type.
     */
    public function get(string $type): ?BlockInterface
    {
        return $this->blocks->get($type);
    }

    /**
     * Check if a block type is registered.
     */
    public function has(string $type): bool
    {
        return $this->blocks->has($type);
    }

    /**
     * Get all registered block types.
     */
    public function all(): Collection
    {
        return $this->blocks;
    }

    /**
     * Get all registered block type names.
     */
    public function types(): array
    {
        return $this->blocks->keys()->toArray();
    }

    /**
     * Render a block by type with attributes.
     */
    public function render(string $type, array $attributes = []): string
    {
        $block = $this->get($type);

        if (! $block) {
            return $this->renderUnknownBlock($type, $attributes);
        }

        return $block->render($attributes);
    }

    /**
     * Render an unknown block fallback.
     */
    protected function renderUnknownBlock(string $type, array $attributes): string
    {
        return "<!-- Unknown block type: {$type} -->";
    }

    /**
     * Render a block from variadic arguments (type, attributes).
     *
     * @param  mixed  ...$args
     */
    public function renderArray(...$args): string
    {
        $type = $args[0] ?? '';
        $attributes = $args[1] ?? [];

        return $this->render($type, $attributes);
    }
}
