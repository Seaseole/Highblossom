<?php

declare(strict_types=1);

namespace App\Domains\Content\Actions\Blocks;

use App\Domains\Content\Services\BlockRegistry;
use App\Domains\Content\Models\ContentBlock;
use Illuminate\Database\Eloquent\Model;

final class AddBlock
{
    public function __construct(
        private readonly BlockRegistry $blockRegistry
    ) {}

    public function execute(Model $contentModel, string $blockTypeId, ?int $position = null): ContentBlock
    {
        $blockType = $this->blockRegistry->find($blockTypeId);

        if ($blockType === null) {
            throw new \InvalidArgumentException("Block type '{$blockTypeId}' not found.");
        }

        // Get max sort order if position not specified
        if ($position === null) {
            $maxOrder = $contentModel->contentBlocks()->max('sort_order') ?? -1;
            $position = $maxOrder + 1;
        } else {
            // Shift existing blocks to make room
            $contentModel->contentBlocks()
                ->where('sort_order', '>=', $position)
                ->increment('sort_order');
        }

        return $contentModel->contentBlocks()->create([
            'type' => $blockTypeId,
            'content' => $blockType::defaultData(),
            'sort_order' => $position,
            'is_visible' => true,
        ]);
    }
}
