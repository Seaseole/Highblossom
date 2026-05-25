<?php

declare(strict_types=1);

namespace App\Services;

use App\Actions\Content\RelocateTempUploadsAction;
use App\Models\Post;
use Illuminate\Http\Request;

final class PostService
{
    public function __construct(
        private readonly RelocateTempUploadsAction $relocateAction,
    ) {}

    public function create(array $data, Request $request): Post
    {
        $this->handleFeaturedImage($data, $request);
        $this->relocateContentImages($data);

        $post = Post::create($data);

        $this->syncRelations($post, $data);

        return $post;
    }

    public function update(Post $post, array $data, Request $request): Post
    {
        $this->handleFeaturedImageUpdate($data, $request, $post);
        $this->relocateContentImages($data);

        $post->update($data);

        $this->syncRelations($post, $data);

        return $post->fresh();
    }

    public function delete(Post $post): void
    {
        $post->delete();
    }

    private function handleFeaturedImage(array &$data, Request $request): void
    {
        if ($request->hasFile('featured_image')) {
            $path = $request->file('featured_image')->store('uploads/blog', 'public');
            $data['featured_image_path'] = $path;
            $data['featured_image_url'] = null;
        }
    }

    private function handleFeaturedImageUpdate(array &$data, Request $request, Post $post): void
    {
        if ($request->boolean('delete_featured_image')) {
            $data['featured_image_path'] = null;
            $data['featured_image_url'] = null;
        } elseif ($request->hasFile('featured_image')) {
            $path = $request->file('featured_image')->store('uploads/blog', 'public');
            $data['featured_image_path'] = $path;
            $data['featured_image_url'] = null;
        }
    }

    private function relocateContentImages(array &$data): void
    {
        if (! empty($data['content'])) {
            $data['content'] = $this->relocateAction->execute($data['content']);
        }
    }

    private function syncRelations(Post $post, array $data): void
    {
        if (isset($data['categories'])) {
            $post->categories()->sync($data['categories']);
        }

        if (isset($data['tags'])) {
            $post->tags()->sync($data['tags']);
        }
    }
}
