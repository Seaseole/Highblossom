<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Content\StoreBlockContentRequest;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Services\PostService;
use Illuminate\Http\RedirectResponse;
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
     * Display a paginated list of blog posts.
     */
    public function index(): View
    {
        $posts = Post::query()->latest()->paginate(15);

        return view('admin.blog.posts.index', compact('posts'));
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
