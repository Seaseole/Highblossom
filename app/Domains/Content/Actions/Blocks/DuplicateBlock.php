<?php

declare(strict_types=1);

namespace App\Domains\Content\Actions\Blocks;

use App\Domains\Content\Models\ContentBlock;
use Illuminate\Support\Facades\Cache;

final class DuplicateBlock
{
    public function execute(ContentBlock $original): ContentBlock
    {
        $parent = $original->blockable;

        // Shift blocks to make room
        $parent->contentBlocks()
            ->where('sort_order', '>=', $original->sort_order + 1)
            ->increment('sort_order');

        // Create duplicate
        $duplicate = $parent->contentBlocks()->create([
            'type' => $original->type,
            'content' => $original->content,
            'sort_order' => $original->sort_order + 1,
            'is_visible' => $original->is_visible,
        ]);

        // Clear cache
        Cache::forget("post_{$parent->slug}");
        Cache::forget("page_{$parent->slug}");

        return $duplicate;
    }
}
