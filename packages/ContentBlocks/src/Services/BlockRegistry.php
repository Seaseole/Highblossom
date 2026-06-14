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
}
