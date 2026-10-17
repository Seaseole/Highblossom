<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\BulkPostStatusRequest;
use App\Http\Requests\Admin\PostStatusRequest;
use App\Http\Requests\Content\StoreBlockContentRequest;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Services\PostService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Manage CRUD operations for blog posts.
 */
final class PostController
{
    public function __construct(
        private readonly PostService $postService,
    ) {}

    /**
     * Display a paginated list of blog posts, optionally filtered by author.
     */
    public function index(Request $request): View
    {
        $posts = Post::with(['author', 'categories', 'tags'])
            ->when($request->integer('user'), fn ($query, int $userId) => $query->where('user_id', $userId))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.blog.posts.index', [
            'posts' => $posts,
            'authorFilter' => $request->integer('user') ?: null,
        ]);
    }

    /**
     * Show the form for creating a new blog post.
     */
    public function create(): View
    {
        $categories = Category::all();
        $tags = Tag::all();

        return view('admin.blog.posts.create', compact('categories', 'tags'));
    }

    /**
     * Store a newly created blog post in storage.
     *
     * @return RedirectResponse
     */
    public function store(StoreBlockContentRequest $request)
    {
        $this->postService->create($request->validatedContent(), $request);

        return redirect()
            ->route('admin.posts.index')
            ->with('success', __('messages.post_created'));
    }

    /**
     * Show the form for editing the specified blog post.
     */
    public function edit(Post $post): View
    {
        $categories = Category::all();
        $tags = Tag::all();
        $post->load('categories', 'tags');

        return view('admin.blog.posts.edit', compact('post', 'categories', 'tags'));
    }

    /**
     * Update the specified blog post in storage.
     *
     * @return RedirectResponse
     */
    public function update(StoreBlockContentRequest $request, Post $post)
    {
        $this->postService->update($post, $request->validatedContent(), $request);

        return redirect()
            ->route('admin.posts.index')
            ->with('success', __('messages.post_updated'));
    }

    /**
     * Move a single post between draft, published and archived from the index.
     */
    public function updateStatus(PostStatusRequest $request, Post $post): JsonResponse
    {
        $status = $request->validated('status');
        $post->loadMissing('categories');

        if ($status === 'published' && ($missing = $post->missingPublishRequirements()) !== []) {
            return $this->publishBlockedResponse($missing);
        }

        $this->postService->updateStatus($post, $status);

        return response()->json([
            'statuses' => [$post->getKey() => $this->statusKey($post)],
            'message' => $this->statusMessage($post, $status),
        ]);
    }

    /**
     * Move several posts between workflow states at once.
     */
    public function bulkStatus(BulkPostStatusRequest $request): JsonResponse
    {
        $status = $request->validated('status');
        $posts = Post::with('categories')->whereIn('id', $request->validated('posts'))->get();

        if ($status === 'published') {
            $blocked = $posts->filter(fn (Post $post) => $post->missingPublishRequirements() !== []);

            if ($blocked->isNotEmpty()) {
                return response()->json([
                    'message' => __('messages.posts_publish_blocked_bulk', ['count' => $blocked->count()]),
                ], 422);
            }
        }

        $changed = $this->postService->bulkUpdateStatus($posts, $status);

        return response()->json([
            'changed' => $changed,
            'statuses' => $posts->mapWithKeys(fn (Post $post) => [$post->getKey() => $this->statusKey($post)]),
            'message' => __('messages.posts_status_updated', [
                'count' => $changed,
                'status' => $status,
            ]),
        ]);
    }

    /**
     * The pill the row should show, which distinguishes a future-dated post.
     */
    private function statusKey(Post $post): string
    {
        return $post->isScheduled() ? 'scheduled' : $post->status;
    }

    /**
     * Toast copy for a completed transition.
     */
    private function statusMessage(Post $post, string $status): string
    {
        return match ($status) {
            'published' => __('messages.post_published', ['title' => $post->title]),
            'archived' => __('messages.post_archived', ['title' => $post->title]),
            default => __('messages.post_unpublished', ['title' => $post->title]),
        };
    }

    /**
     * Build the shared rejection payload for an unfinished post.
     *
     * @param array<int, string> $missing
     */
    private function publishBlockedResponse(array $missing): JsonResponse
    {
        return response()->json([
            'message' => __('messages.posts_publish_blocked', ['fields' => implode(', ', $missing)]),
        ], 422);
    }

    /**
     * Remove the specified blog post from storage.
     *
     * @return RedirectResponse
     */
    public function destroy(Post $post)
    {
        $this->postService->delete($post);

        return redirect()
            ->route('admin.posts.index')
            ->with('success', __('messages.post_deleted'));
    }
}
