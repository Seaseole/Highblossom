<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\GlassSubCategoryRequest;
use App\Models\GlassSubCategory;
use App\Models\GlassType;
use App\Services\GlassSubCategoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/**
 * Manage CRUD operations for glass sub-categories.
 */
final class GlassSubCategoryController
{
    public function __construct(
        private readonly GlassSubCategoryService $glassSubCategoryService,
    ) {}

    /**
     * Display a listing of glass sub-categories.
     */
    public function index(): View
    {
        $glassSubCategories = $this->glassSubCategoryService->getAll();

        return view('admin.glass-sub-categories.index', compact('glassSubCategories'));
    }

    /**
     * Show the form for creating a new glass sub-category.
     */
    public function create(): View
    {
        $glassTypes = GlassType::active()->ordered()->with('subCategories')->get();

        return view('admin.glass-sub-categories.create', compact('glassTypes'));
    }

    /**
     * Store a newly created glass sub-category in storage.
     *
     * @return RedirectResponse|JsonResponse
     */
    public function store(GlassSubCategoryRequest $request)
    {
        $subCategory = $this->glassSubCategoryService->create($request->validated());

        if ($request->wantsJson()) {
            return $this->presented($subCategory, 201);
        }

        return redirect()
            ->route('admin.glass-sub-categories.index')
            ->with('success', __('messages.glass_sub_category_created'));
    }

    /**
     * Show the form for editing the specified glass sub-category.
     */
    public function edit(GlassSubCategory $glassSubCategory): View
    {
        $glassTypes = GlassType::active()->ordered()->with('subCategories')->get();

        return view('admin.glass-sub-categories.edit', compact('glassSubCategory', 'glassTypes'));
    }

    /**
     * Update the specified glass sub-category in storage.
     *
     * @return RedirectResponse|JsonResponse
     */
    public function update(GlassSubCategoryRequest $request, GlassSubCategory $glassSubCategory)
    {
        $subCategory = $this->glassSubCategoryService->update($glassSubCategory, $request->validated());

        if ($request->wantsJson()) {
            return $this->presented($subCategory);
        }

        return redirect()
            ->route('admin.glass-sub-categories.index')
            ->with('success', __('messages.glass_sub_category_updated'));
    }

    /**
     * Remove the specified glass sub-category from storage.
     *
     * @return RedirectResponse|JsonResponse
     */
    public function destroy(GlassSubCategory $glassSubCategory)
    {
        try {
            $this->glassSubCategoryService->delete($glassSubCategory);
        } catch (\Exception $e) {
            if (request()->wantsJson()) {
                return response()->json(['message' => $e->getMessage()], 422);
            }

            return redirect()
                ->route('admin.glass-sub-categories.index')
                ->with('error', $e->getMessage());
        }

        if (request()->wantsJson()) {
            return response()->json(['id' => $glassSubCategory->getKey()]);
        }

        return redirect()
            ->route('admin.glass-sub-categories.index')
            ->with('success', __('messages.glass_sub_category_deleted'));
    }

    /**
     * Shape a sub-category for the modal on the Edit Glass Type page.
     */
    private function presented(GlassSubCategory $subCategory, int $status = 200): JsonResponse
    {
        return response()->json([
            'id' => $subCategory->getKey(),
            'name' => $subCategory->name,
            'slug' => $subCategory->slug,
            'sort_order' => $subCategory->sort_order,
            'is_active' => $subCategory->is_active,
        ], $status);
    }

    /**
     * Toggle sub-category active status.
     */
    public function toggleStatus(GlassSubCategory $glassSubCategory): JsonResponse
    {
        $glassSubCategory = $this->glassSubCategoryService->toggleStatus($glassSubCategory);

        return response()->json([
            'success' => true,
            'is_active' => $glassSubCategory->is_active,
        ]);
    }

    /**
     * Get sub-categories by glass type for AJAX requests.
     */
    public function getByGlassType(int $glassTypeId): JsonResponse
    {
        $subCategories = $this->glassSubCategoryService->getByGlassType($glassTypeId);

        return response()->json([
            'sub_categories' => $subCategories,
        ]);
    }

    /**
     * Reorder glass sub-categories.
     */
    public function reorder(): JsonResponse
    {
        $subCategoryIds = request()->input('sub_category_ids', []);

        $this->glassSubCategoryService->reorder($subCategoryIds);

        return response()->json([
            'success' => true,
        ]);
    }
}
