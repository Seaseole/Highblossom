<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Domains\Content\Models\GlassType;
use App\Http\Requests\Admin\GlassTypeRequest;
use App\Services\GlassSubCategoryService;
use App\Services\GlassTypeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

final readonly class GlassTypeController
{
    public function __construct(
        private GlassTypeService $glassTypeService,
        private GlassSubCategoryService $glassSubCategoryService,
    ) {}

    public function index(): View
    {
        $glassTypes = GlassType::query()->ordered()->with('subCategories')->paginate(15);

        return view('admin.glass-types.index', compact('glassTypes'));
    }

    public function create(): View
    {
        return view('admin.glass-types.create');
    }

    public function store(GlassTypeRequest $request): RedirectResponse
    {
        $this->glassTypeService->create($request->validated());

        return redirect()
            ->route('admin.glass-types.index')
            ->with('success', __('messages.glass_type_created'));
    }

    public function edit(GlassType $glassType): View
    {
        $glassSubCategories = $this->glassSubCategoryService->getByGlassType($glassType);

        return view('admin.glass-types.edit', compact('glassType', 'glassSubCategories'));
    }

    public function update(GlassTypeRequest $request, GlassType $glassType): RedirectResponse
    {
        $this->glassTypeService->update($glassType, $request->validated());

        return redirect()
            ->route('admin.glass-types.index')
            ->with('success', __('messages.glass_type_updated'));
    }

    public function destroy(GlassType $glassType): RedirectResponse
    {
        $this->glassTypeService->delete($glassType);

        return redirect()
            ->route('admin.glass-types.index')
            ->with('success', __('messages.glass_type_deleted'));
    }

    /**
     * Get sub-categories for a glass type (AJAX endpoint).
     */
    public function getSubCategories(GlassType $glassType): JsonResponse
    {
        $subCategories = $this->glassSubCategoryService->getByGlassType($glassType);

        return response()->json([
            'sub_categories' => $subCategories,
        ]);
    }
}
