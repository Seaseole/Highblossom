<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Domains\Content\Models\GlassType;
use App\Http\Requests\Admin\GlassTypeRequest;
use App\Services\GlassTypeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

final class GlassTypeController
{
    public function __construct(
        private readonly GlassTypeService $glassTypeService,
    ) {}

    public function index(): View
    {
        $glassTypes = GlassType::query()->ordered()->paginate(15);

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
        return view('admin.glass-types.edit', compact('glassType'));
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
}
