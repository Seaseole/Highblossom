<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Domains\Content\Models\GlassType;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

final class GlassTypeController
{
    public function index(): View
    {
        $glassTypes = GlassType::query()->ordered()->paginate(15);

        return view('admin.glass-types.index', compact('glassTypes'));
    }

    public function create(): View
    {
        return view('admin.glass-types.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['slug'] = \Illuminate\Support\Str::slug($validated['name']);
        $validated['is_active'] = $request->has('is_active');

        GlassType::create($validated);

        return redirect()
            ->route('admin.glass-types.index')
            ->with('success', 'Glass type created successfully');
    }

    public function edit(GlassType $glassType): View
    {
        return view('admin.glass-types.edit', compact('glassType'));
    }

    public function update(Request $request, GlassType $glassType): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['slug'] = \Illuminate\Support\Str::slug($validated['name']);
        $validated['is_active'] = $request->has('is_active');

        $glassType->update($validated);

        return redirect()
            ->route('admin.glass-types.index')
            ->with('success', 'Glass type updated successfully');
    }

    public function destroy(GlassType $glassType): RedirectResponse
    {
        $glassType->delete();

        return redirect()
            ->route('admin.glass-types.index')
            ->with('success', 'Glass type deleted successfully');
    }
}
