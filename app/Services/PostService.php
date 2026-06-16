<?php

declare(strict_types=1);

namespace App\Services;

use App\Actions\Content\RelocateTempUploadsAction;
use App\Models\Poll;
use App\Models\Post;
use Illuminate\Http\Request;

/**
 * Service for managing blog posts with content blocks, featured images, and relations.
 */
final class PostService
{
    public function __construct(
        private readonly RelocateTempUploadsAction $relocateAction,
    ) {}

    /**
     * Create a new post with featured image, content image relocation, and relations.
     */
    public function create(array $data, Request $request): Post
    {
        $this->handleFeaturedImage($data, $request);
        $this->relocateContentImages($data);
        $this->syncPolls($data);

        $post = Post::create($data);

        $this->syncRelations($post, $data);

        return $post;
    }

    /**
     * Update an existing post with image handling and relation syncing.
     */
    public function update(Post $post, array $data, Request $request): Post
    {
        $this->handleFeaturedImageUpdate($data, $request, $post);
        $this->relocateContentImages($data);
        $this->syncPolls($data);

        $post->update($data);

        $this->syncRelations($post, $data);

        return $post->fresh();
    }

    /**
     * Sync poll blocks in content with the polls table.
     */
    private function syncPolls(array &$data): void
    {
        if (empty($data['content'])) {
            return;
        }

        foreach ($data['content'] as &$block) {
            if ($block['type'] === 'poll') {
                $attrs = $block['attributes'];

                $poll = Poll::updateOrCreate(
                    ['id' => $attrs['poll_id'] ?? null],
                    [
                        'question' => $attrs['question'],
                        'options' => $attrs['options'],
                        'allow_multiple' => $attrs['allow_multiple'] ?? false,
                        'show_results' => $attrs['show_results'] ?? true,
                    ]
                );

                $block['attributes']['poll_id'] = $poll->id;
            }
        }
    }

    /**
     * Delete a post.
     */
    public function delete(Post $post): void
    {
        $post->delete();
    }

    /**
     * Handle featured image upload during post creation.
     */
    private function handleFeaturedImage(array &$data, Request $request): void
    {
        if ($request->hasFile('featured_image')) {
            $path = $request->file('featured_image')->store('uploads/blog', 'public');
            $data['featured_image_path'] = $path;
            $data['featured_image_url'] = null;
        }
    }

    /**
     * Handle featured image update or deletion during post update.
     */
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

    /**
     * Relocate temporary uploaded images to permanent storage.
     */
    private function relocateContentImages(array &$data): void
    {
        if (! empty($data['content'])) {
            $data['content'] = $this->relocateAction->execute($data['content']);
        }
    }

    /**
     * Sync categories and tags relationships for a post.
     */
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
