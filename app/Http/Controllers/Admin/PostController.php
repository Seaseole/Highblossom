<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Http\Requests\Content\StoreBlockContentRequest;
use App\Services\PostService;
use Illuminate\View\View;

final class PostController
{
    public function __construct(
        private readonly PostService $postService,
    ) {}

    public function index(): View
    {
        $posts = Post::query()->latest()->paginate(15);

        return view('admin.blog.posts.index', compact('posts'));
    }

    public function create(): View
    {
        $categories = Category::all();
        $tags = Tag::all();

        return view('admin.blog.posts.create', compact('categories', 'tags'));
    }

    public function store(StoreBlockContentRequest $request)
    {
        $this->postService->create($request->validatedContent(), $request);

        return redirect()
            ->route('admin.posts.index')
            ->with('success', __('messages.post_created'));
    }

    public function edit(Post $post): View
    {
        $categories = Category::all();
        $tags = Tag::all();
        $post->load('categories', 'tags');

        return view('admin.blog.posts.edit', compact('post', 'categories', 'tags'));
    }

    public function update(StoreBlockContentRequest $request, Post $post)
    {
        $this->postService->update($post, $request->validatedContent(), $request);

        return redirect()
            ->route('admin.posts.index')
            ->with('success', __('messages.post_updated'));
    }

    public function destroy(Post $post)
    {
        $this->postService->delete($post);

        return redirect()
            ->route('admin.posts.index')
            ->with('success', __('messages.post_deleted'));
    }
}
