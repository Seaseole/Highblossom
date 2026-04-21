<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Domains\Content\Models\ServiceType;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

final class ServiceTypeController
{
    public function index(): View
    {
        $serviceTypes = ServiceType::query()->ordered()->paginate(15);

        return view('admin.service-types.index', compact('serviceTypes'));
    }

    public function create(): View
    {
        return view('admin.service-types.create');
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

        ServiceType::create($validated);

        return redirect()
            ->route('admin.service-types.index')
            ->with('success', 'Service type created successfully');
    }

    public function edit(ServiceType $serviceType): View
    {
        return view('admin.service-types.edit', compact('serviceType'));
    }

    public function update(Request $request, ServiceType $serviceType): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['slug'] = \Illuminate\Support\Str::slug($validated['name']);
        $validated['is_active'] = $request->has('is_active');

        $serviceType->update($validated);

        return redirect()
            ->route('admin.service-types.index')
            ->with('success', 'Service type updated successfully');
    }

    public function destroy(ServiceType $serviceType): RedirectResponse
    {
        $serviceType->delete();

        return redirect()
            ->route('admin.service-types.index')
            ->with('success', 'Service type deleted successfully');
    }
}
