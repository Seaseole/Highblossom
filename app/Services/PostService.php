<?php

declare(strict_types=1);

namespace App\Services;

use App\Actions\Content\RelocateTempUploadsAction;
use App\Models\Poll;
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
        $this->syncPolls($data);

        $post = Post::create($data);

        $this->syncRelations($post, $data);

        return $post;
    }

    public function update(Post $post, array $data, Request $request): Post
    {
        $this->handleFeaturedImageUpdate($data, $request, $post);
        $this->relocateContentImages($data);
        $this->syncPolls($data);

        $post->update($data);

        $this->syncRelations($post, $data);

        return $post->fresh();
    }

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
