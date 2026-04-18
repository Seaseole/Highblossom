<?php

declare(strict_types=1);

namespace App\Domains\Content\Actions\Posts;

use App\Domains\Content\Models\Post;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

final class DuplicatePost
{
    public function execute(Post $original): Post
    {
        return DB::transaction(function () use ($original) {
            // Create new post as a copy
            $newPost = Post::create([
                'title' => $original->title . ' (Copy)',
                'slug' => Str::slug($original->slug . '-copy-' . uniqid()),
                'excerpt' => $original->excerpt,
                'featured_image' => $original->featured_image,
                'author_id' => auth()->id() ?? $original->author_id,
                'category_id' => $original->category_id,
                'seo_metadata' => $original->seo_metadata,
                'is_published' => false,
                'published_at' => null,
                'is_featured' => false,
            ]);

            // Copy all content blocks
            foreach ($original->contentBlocks as $block) {
                $newPost->contentBlocks()->create([
                    'type' => $block->type,
                    'content' => $block->content,
                    'sort_order' => $block->sort_order,
                    'is_visible' => $block->is_visible,
                ]);
            }

            // Copy tags
            $tagIds = $original->tags()->pluck('tags.id')->toArray();
            $newPost->tags()->sync($tagIds);

            return $newPost->fresh();
        });
    }
}
