<?php

declare(strict_types=1);

namespace App\Domains\Content\Actions\Posts;

use App\Domains\Content\Models\Post;
use App\Domains\Content\Models\PostRevision;
use App\Models\User;

final class CreateRevision
{
    /**
     * Create a revision snapshot of the current post state
     */
    public function execute(Post $post, ?User $user = null, ?string $note = null): PostRevision
    {
        $contentBlocks = $post->contentBlocks
            ->sortBy('sort_order')
            ->map(fn ($block) => [
                'type' => $block->type,
                'content' => $block->content,
                'is_visible' => $block->is_visible,
            ])
            ->toArray();

        return PostRevision::create([
            'post_id' => $post->id,
            'user_id' => $user?->id,
            'title' => $post->title,
            'excerpt' => $post->excerpt,
            'content_blocks' => $contentBlocks,
            'seo_metadata' => $post->seo_metadata,
            'revision_note' => $note,
            'created_at' => now(),
        ]);
    }
}
