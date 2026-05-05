<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Domains\Content\Models\Category;
use App\Http\Requests\Admin\CategoryRequest;
use App\Services\CategoryService;
use Illuminate\View\View;

final class CategoryController
{
    public function __construct(
        private readonly CategoryService $categoryService,
    ) {}

    public function index(): View
    {
        $categories = Category::query()->latest()->paginate(15);

        return view('admin.blog.categories.index', compact('categories'));
    }

    public function create(): View
    {
        return view('admin.blog.categories.create');
    }

    public function store(CategoryRequest $request)
    {
        $this->categoryService->create($request->validated());

        return redirect()
            ->route('admin.categories.index')
            ->with('success', __('messages.category_created'));
    }

    public function edit(Category $category): View
    {
        return view('admin.blog.categories.edit', compact('category'));
    }

    public function update(CategoryRequest $request, Category $category)
    {
        $this->categoryService->update($category, $request->validated());

        return redirect()
            ->route('admin.categories.index')
            ->with('success', __('messages.category_updated'));
    }

    public function destroy(Category $category)
    {
        $this->categoryService->delete($category);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', __('messages.category_deleted'));
    }
}
