<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Domains\Content\Models\Tag;
use App\Http\Requests\Admin\TagRequest;
use App\Services\TagService;
use Illuminate\View\View;

final class TagController
{
    public function __construct(
        private readonly TagService $tagService,
    ) {}

    public function index(): View
    {
        $tags = Tag::query()->latest()->paginate(15);

        return view('admin.blog.tags.index', compact('tags'));
    }

    public function create(): View
    {
        return view('admin.blog.tags.create');
    }

    public function store(TagRequest $request)
    {
        $this->tagService->create($request->validated());

        return redirect()
            ->route('admin.tags.index')
            ->with('success', __('messages.tag_created'));
    }

    public function edit(Tag $tag): View
    {
        return view('admin.blog.tags.edit', compact('tag'));
    }

    public function update(TagRequest $request, Tag $tag)
    {
        $this->tagService->update($tag, $request->validated());

        return redirect()
            ->route('admin.tags.index')
            ->with('success', __('messages.tag_updated'));
    }

    public function destroy(Tag $tag)
    {
        $this->tagService->delete($tag);

        return redirect()
            ->route('admin.tags.index')
            ->with('success', __('messages.tag_deleted'));
    }
}
