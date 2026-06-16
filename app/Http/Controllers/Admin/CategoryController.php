<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\CategoryRequest;
use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/**
 * Manage CRUD operations for blog categories.
 */
final class CategoryController
{
    public function __construct(
        private readonly CategoryService $categoryService,
    ) {}

    /**
     * Display a paginated list of categories.
     */
    public function index(): View
    {
        $categories = Category::query()->latest()->paginate(15);

        return view('admin.blog.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new category.
     */
    public function create(): View
    {
        return view('admin.blog.categories.create');
    }

    /**
     * Store a newly created category in storage.
     *
     * @return RedirectResponse
     */
    public function store(CategoryRequest $request)
    {
        $this->categoryService->create($request->validated());

        return redirect()
            ->route('admin.categories.index')
            ->with('success', __('messages.category_created'));
    }

    /**
     * Show the form for editing the specified category.
     */
    public function edit(Category $category): View
    {
        return view('admin.blog.categories.edit', compact('category'));
    }

    /**
     * Update the specified category in storage.
     *
     * @return RedirectResponse
     */
    public function update(CategoryRequest $request, Category $category)
    {
        $this->categoryService->update($category, $request->validated());

        return redirect()
            ->route('admin.categories.index')
            ->with('success', __('messages.category_updated'));
    }

    /**
     * Remove the specified category from storage.
     *
     * @return RedirectResponse
     */
    public function destroy(Category $category)
    {
        $this->categoryService->delete($category);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', __('messages.category_deleted'));
    }
}
