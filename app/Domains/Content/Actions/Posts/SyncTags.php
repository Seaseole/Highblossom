<?php

declare(strict_types=1);

namespace App\Domains\Content\Actions\Posts;

use App\Domains\Content\Models\Post;
use App\Domains\Content\Models\Tag;
use Illuminate\Support\Str;

final class SyncTags
{
    /**
     * Sync tags on a post. Accepts array of tag names or IDs.
     *
     * @param array<int|string> $tags Array of tag names or IDs
     */
    public function execute(Post $post, array $tags): void
    {
        $tagIds = [];

        foreach ($tags as $tag) {
            if (is_numeric($tag)) {
                $tagIds[] = (int) $tag;
            } else {
                $tagModel = Tag::firstOrCreate(
                    ['slug' => Str::slug($tag)],
                    ['name' => $tag]
                );
                $tagIds[] = $tagModel->id;
            }
        }

        $post->tags()->sync($tagIds);
    }
}
