<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Actions\Content\RelocateTempUploadsAction;
use App\Domains\Content\Models\Post;
use App\Domains\Content\Models\Category;
use App\Domains\Content\Models\Tag;
use App\Http\Requests\Content\StoreBlockContentRequest;
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

    public function store(StoreBlockContentRequest $request, RelocateTempUploadsAction $relocateAction)
    {
        $validated = $request->validatedContent();

        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            $path = $request->file('featured_image')->store('uploads/blog', 'public');
            $validated['featured_image_path'] = $path;
            $validated['featured_image_url'] = null;
        }

        // Relocate temp uploads to permanent storage
        if (!empty($validated['content'])) {
            $validated['content'] = $relocateAction->execute($validated['content']);
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

    public function update(StoreBlockContentRequest $request, Post $post, RelocateTempUploadsAction $relocateAction)
    {
        $validated = $request->validatedContent();

        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            $path = $request->file('featured_image')->store('uploads/blog', 'public');
            $validated['featured_image_path'] = $path;
            $validated['featured_image_url'] = null;
        }

        // Handle delete featured image
        if ($request->boolean('delete_featured_image')) {
            $validated['featured_image_path'] = null;
            $validated['featured_image_url'] = null;
        }

        // Relocate temp uploads to permanent storage
        if (!empty($validated['content'])) {
            $validated['content'] = $relocateAction->execute($validated['content']);
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
