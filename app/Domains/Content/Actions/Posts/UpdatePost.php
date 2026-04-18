<?php

declare(strict_types=1);

namespace App\Domains\Content\Actions\Posts;

use App\Domains\Content\Models\Post;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

final class UpdatePost
{
    public function execute(Post $post, array $data): Post
    {
        return DB::transaction(function () use ($post, $data) {
            // Handle publishing transition
            $wasPublished = $post->is_published;
            $isPublishing = ($data['is_published'] ?? false) && ! $wasPublished;

            $updateData = [
                'title' => $data['title'] ?? $post->title,
                'slug' => $data['slug'] ?? $post->slug,
                'excerpt' => $data['excerpt'] ?? $post->excerpt,
                'featured_image' => $data['featured_image'] ?? $post->featured_image,
                'category_id' => $data['category_id'] ?? $post->category_id,
                'seo_metadata' => $data['seo_metadata'] ?? $post->seo_metadata,
                'is_published' => $data['is_published'] ?? $post->is_published,
                'is_featured' => $data['is_featured'] ?? $post->is_featured,
            ];

            if ($isPublishing) {
                $updateData['published_at'] = now();
            }

            $post->update($updateData);

            // Sync tags if provided
            if (isset($data['tags'])) {
                app(SyncTags::class)->execute($post, $data['tags']);
            }

            // Clear cache
            $this->clearPostCache($post);

            return $post->fresh();
        });
    }

    private function clearPostCache(Post $post): void
    {
        Cache::forget("post_{$post->slug}");
        Cache::forget("post_render_{$post->id}");
    }
}
