<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PostStatusRequest;
use App\Http\Resources\Api\PostResource;
use App\Models\Post;
use App\Services\PostService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * Read and moderate blog posts for the staff app.
 *
 * Create/edit of block content stays on the web admin (the block builder is not
 * reproduced on mobile). Status changes reuse PostService and honour the same
 * publish requirements, so an unfinished post cannot be published from the app.
 * Authorization mirrors the web admin: `view blog` to read, `update blog` to
 * change status, `delete blog` to remove.
 */
final class PostController extends Controller
{
    public function __construct(private readonly PostService $postService) {}

    /**
     * List posts, optionally filtered by status/author and a title/excerpt search.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $posts = Post::query()
            ->with(['author', 'categories', 'tags'])
            ->when($request->query('status'), fn ($q, $status) => $q->where('status', $status))
            ->when($request->integer('user'), fn ($q, $userId) => $q->where('user_id', $userId))
            ->when($request->query('search'), fn ($q, $search) => $q->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('excerpt', 'like', "%{$search}%");
            }))
            // scopeLatest orders by published_at only; add an id tie-break for determinism.
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->paginate(min(100, max(1, $request->integer('per_page', 15))));

        return PostResource::collection($posts);
    }

    /**
     * Show one post with its author and taxonomy.
     */
    public function show(Post $post): PostResource
    {
        $post->load(['author', 'categories', 'tags']);

        return new PostResource($post);
    }

    /**
     * Move a post between draft, published and archived.
     */
    public function updateStatus(PostStatusRequest $request, Post $post): JsonResponse
    {
        $status = $request->validated('status');
        $post->loadMissing('categories');

        if ($status === 'published' && ($missing = $post->missingPublishRequirements()) !== []) {
            return response()->json([
                'message' => __('messages.posts_publish_blocked', ['fields' => implode(', ', $missing)]),
            ], 422);
        }

        $post = $this->postService->updateStatus($post, $status);
        $post->load(['author', 'categories', 'tags']);

        return response()->json([
            'data' => new PostResource($post),
            'message' => 'Post status updated.',
        ]);
    }

    /**
     * Delete a post.
     */
    public function destroy(Post $post): JsonResponse
    {
        $this->postService->delete($post);

        return response()->json(['message' => __('messages.post_deleted')]);
    }
}
