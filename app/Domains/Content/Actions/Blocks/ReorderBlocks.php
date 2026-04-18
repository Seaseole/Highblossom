<?php

declare(strict_types=1);

namespace App\Domains\Content\Actions\Blocks;

use App\Domains\Content\Models\ContentBlock;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

final class ReorderBlocks
{
    /**
     * Reorder blocks using an array of block IDs in the desired order.
     *
     * @param array<int> $orderedBlockIds
     */
    public function execute(Model $contentModel, array $orderedBlockIds): void
    {
        DB::transaction(function () use ($contentModel, $orderedBlockIds) {
            foreach ($orderedBlockIds as $index => $blockId) {
                ContentBlock::where('id', $blockId)
                    ->where('blockable_id', $contentModel->id)
                    ->where('blockable_type', $contentModel->getMorphClass())
                    ->update(['sort_order' => $index]);
            }

            // Clear cache
            $this->clearCache($contentModel);
        });
    }

    /**
     * Move a single block to a new position.
     */
    public function moveToPosition(ContentBlock $block, int $newPosition): void
    {
        $parent = $block->blockable;

        if ($parent === null) {
            return;
        }

        DB::transaction(function () use ($block, $parent, $newPosition) {
            $currentPosition = $block->sort_order;

            if ($newPosition > $currentPosition) {
                // Moving down: decrement blocks between current and new position
                $parent->contentBlocks()
                    ->where('sort_order', '>', $currentPosition)
                    ->where('sort_order', '<=', $newPosition)
                    ->decrement('sort_order');
            } elseif ($newPosition < $currentPosition) {
                // Moving up: increment blocks between new position and current
                $parent->contentBlocks()
                    ->where('sort_order', '>=', $newPosition)
                    ->where('sort_order', '<', $currentPosition)
                    ->increment('sort_order');
            }

            $block->update(['sort_order' => $newPosition]);

            // Clear cache
            $this->clearCache($parent);
        });
    }

    private function clearCache(Model $contentModel): void
    {
        if (method_exists($contentModel, 'getRouteParameters')) {
            Cache::forget("post_{$contentModel->slug}");
            Cache::forget("page_{$contentModel->slug}");
        }
    }
}
