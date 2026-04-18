<?php

declare(strict_types=1);

namespace App\Domains\Content\Actions\Blocks;

use App\Domains\Content\Services\BlockRegistry;
use App\Domains\Content\Models\ContentBlock;
use Illuminate\Support\Facades\Cache;

final class UpdateBlock
{
    public function __construct(
        private readonly BlockRegistry $blockRegistry
    ) {}

    public function execute(ContentBlock $block, array $data): ContentBlock
    {
        $blockType = $this->blockRegistry->find($block->type);

        if ($blockType === null) {
            throw new \InvalidArgumentException("Block type '{$block->type}' not found.");
        }

        // Validate data
        $validated = validator($data, $blockType::validationRules())->validate();

        // Update the block
        $block->update([
            'content' => array_merge($block->content ?? [], $validated),
        ]);

        // Clear parent content cache
        $this->clearParentCache($block);

        return $block->fresh();
    }

    private function clearParentCache(ContentBlock $block): void
    {
        $parent = $block->blockable;

        if ($parent !== null) {
            Cache::forget("post_{$parent->slug}");
            Cache::forget("page_{$parent->slug}");
        }
    }
}
