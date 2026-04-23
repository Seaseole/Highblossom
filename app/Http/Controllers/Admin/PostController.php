<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Domains\Content\Models\Post;
use App\Domains\Content\Models\Category;
use App\Domains\Content\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class PostController
{
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

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string',
            'content' => 'nullable|string',
            'featured_image_path' => 'nullable|string',
            'featured_image_url' => 'nullable|url|max:500',
            'status' => 'required|in:draft,published',
            'published_at' => 'nullable|date',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
        ]);

        $validated['slug'] = \Illuminate\Support\Str::slug($validated['title']);
        $validated['content'] = isset($validated['content']) && $validated['content'] !== ''
            ? json_decode($validated['content'], true) ?? []
            : [];

        if ($validated['status'] === 'published' && empty($validated['published_at'])) {
            $validated['published_at'] = now();
        }

        $post = Post::create($validated);

        if (!empty($validated['categories'])) {
            $post->categories()->attach($validated['categories']);
        }

        if (!empty($validated['tags'])) {
            $post->tags()->attach($validated['tags']);
        }

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

    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string',
            'content' => 'nullable|string',
            'featured_image_path' => 'nullable|string',
            'featured_image_url' => 'nullable|url|max:500',
            'status' => 'required|in:draft,published',
            'published_at' => 'nullable|date',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
        ]);

        $validated['slug'] = \Illuminate\Support\Str::slug($validated['title']);
        $validated['content'] = isset($validated['content']) && $validated['content'] !== ''
            ? json_decode($validated['content'], true) ?? []
            : [];

        if ($validated['status'] === 'published' && empty($validated['published_at'])) {
            $validated['published_at'] = now();
        }

        $post->update($validated);

        $post->categories()->sync($validated['categories'] ?? []);
        $post->tags()->sync($validated['tags'] ?? []);

        return redirect()
            ->route('admin.posts.index')
            ->with('success', __('messages.post_updated'));
    }

    public function destroy(Post $post)
    {
        $post->delete();

        return redirect()
            ->route('admin.posts.index')
            ->with('success', __('messages.post_deleted'));
    }
}
