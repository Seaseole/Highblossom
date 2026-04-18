<?php

declare(strict_types=1);

namespace App\Domains\Content\Actions\Blocks;

use App\Domains\Content\Models\ContentBlock;
use Illuminate\Support\Facades\Cache;

final class RemoveBlock
{
    public function execute(ContentBlock $block): bool
    {
        $parent = $block->blockable;

        // Delete the block
        $deleted = $block->delete();

        // Reorder remaining blocks to fill the gap
        if ($parent !== null) {
            $parent->contentBlocks()
                ->where('sort_order', '>', $block->sort_order)
                ->decrement('sort_order');

            // Clear cache
            Cache::forget("post_{$parent->slug}");
            Cache::forget("page_{$parent->slug}");
        }

        return $deleted;
    }
}
