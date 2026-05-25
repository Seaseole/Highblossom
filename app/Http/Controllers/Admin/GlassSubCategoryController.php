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

final class GlassSubCategoryController
{
    public function __construct(
        private readonly GlassSubCategoryService $glassSubCategoryService,
    ) {}

    public function index(): View
    {
        $glassSubCategories = $this->glassSubCategoryService->getAll();

        return view('admin.glass-sub-categories.index', compact('glassSubCategories'));
    }

    public function create(): View
    {
        $glassTypes = GlassType::active()->ordered()->with('subCategories')->get();

        return view('admin.glass-sub-categories.create', compact('glassTypes'));
    }

    public function store(GlassSubCategoryRequest $request): RedirectResponse
    {
        $this->glassSubCategoryService->create($request->validated());

        return redirect()
            ->route('admin.glass-sub-categories.index')
            ->with('success', __('messages.glass_sub_category_created'));
    }

    public function edit(GlassSubCategory $glassSubCategory): View
    {
        $glassTypes = GlassType::active()->ordered()->with('subCategories')->get();

        return view('admin.glass-sub-categories.edit', compact('glassSubCategory', 'glassTypes'));
    }

    public function update(GlassSubCategoryRequest $request, GlassSubCategory $glassSubCategory): RedirectResponse
    {
        $this->glassSubCategoryService->update($glassSubCategory, $request->validated());

        return redirect()
            ->route('admin.glass-sub-categories.index')
            ->with('success', __('messages.glass_sub_category_updated'));
    }

    public function destroy(GlassSubCategory $glassSubCategory): RedirectResponse
    {
        try {
            $this->glassSubCategoryService->delete($glassSubCategory);

            return redirect()
                ->route('admin.glass-sub-categories.index')
                ->with('success', __('messages.glass_sub_category_deleted'));
        } catch (\Exception $e) {
            return redirect()
                ->route('admin.glass-sub-categories.index')
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Toggle sub-category status.
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
     * Reorder sub-categories.
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
