<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\TagRequest;
use App\Models\Tag;
use App\Services\TagService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/**
 * Manage CRUD operations for blog tags.
 */
final class TagController
{
    public function __construct(
        private readonly TagService $tagService,
    ) {}

    /**
     * Display a paginated list of tags.
     */
    public function index(): View
    {
        $tags = Tag::query()->latest()->paginate(15);

        return view('admin.blog.tags.index', compact('tags'));
    }

    /**
     * Show the form for creating a new tag.
     */
    public function create(): View
    {
        return view('admin.blog.tags.create');
    }

    /**
     * Store a newly created tag in storage.
     *
     * @return RedirectResponse
     */
    public function store(TagRequest $request)
    {
        $this->tagService->create($request->validated());

        return redirect()
            ->route('admin.tags.index')
            ->with('success', __('messages.tag_created'));
    }

    /**
     * Show the form for editing the specified tag.
     */
    public function edit(Tag $tag): View
    {
        return view('admin.blog.tags.edit', compact('tag'));
    }

    /**
     * Update the specified tag in storage.
     *
     * @return RedirectResponse
     */
    public function update(TagRequest $request, Tag $tag)
    {
        $this->tagService->update($tag, $request->validated());

        return redirect()
            ->route('admin.tags.index')
            ->with('success', __('messages.tag_updated'));
    }

    /**
     * Remove the specified tag from storage.
     *
     * @return RedirectResponse
     */
    public function destroy(Tag $tag)
    {
        $this->tagService->delete($tag);

        return redirect()
            ->route('admin.tags.index')
            ->with('success', __('messages.tag_deleted'));
    }
}
