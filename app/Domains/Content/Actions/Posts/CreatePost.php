<?php

declare(strict_types=1);

namespace App\Domains\Content\Actions\Posts;

use App\Domains\Content\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

final class CreatePost
{
    public function execute(array $data): Post
    {
        return DB::transaction(function () use ($data) {
            $post = Post::create([
                'title' => $data['title'],
                'slug' => $data['slug'] ?? Str::slug($data['title']),
                'excerpt' => $data['excerpt'] ?? null,
                'featured_image' => $data['featured_image'] ?? null,
                'author_id' => $data['author_id'] ?? Auth::id(),
                'category_id' => $data['category_id'] ?? null,
                'seo_metadata' => $data['seo_metadata'] ?? null,
                'is_published' => $data['is_published'] ?? false,
                'published_at' => ($data['is_published'] ?? false) ? now() : null,
                'is_featured' => $data['is_featured'] ?? false,
            ]);

            // Sync tags if provided
            if (! empty($data['tags'])) {
                app(SyncTags::class)->execute($post, $data['tags']);
            }

            return $post;
        });
    }
}
