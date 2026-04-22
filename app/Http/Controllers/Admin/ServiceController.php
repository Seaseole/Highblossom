<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Domains\Content\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

final class ServiceController
{
    public function index(): View
    {
        $services = Service::query()->latest()->paginate(15);

        return view('admin.services.index', compact('services'));
    }

    public function create(): View
    {
        return view('admin.services.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'short_description' => 'required|string',
            'full_description' => 'nullable|string',
            'icon' => 'nullable|string|max:50',
            'features' => 'nullable|string',
            'image_url' => 'nullable|url|max:500',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'image_path' => 'nullable|string',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer',
        ]);

        $validated['slug'] = \Illuminate\Support\Str::slug($validated['title']);
        $validated['features'] = $validated['features'] ? array_map('trim', explode("\n", $validated['features'])) : [];
        $validated['is_active'] = $request->has('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        // Use AJAX uploaded path if provided, otherwise use traditional file upload
        if (!empty($validated['image_path'])) {
            $validated['image_url'] = null; // Clear URL if file is uploaded
        } elseif ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('services', 'public');
            $validated['image_path'] = $imagePath;
            $validated['image_url'] = null; // Clear URL if file is uploaded
        } elseif (!$validated['image_url']) {
            // Use placeholder if no image or URL provided
            $validated['image_path'] = 'placeholder.gif';
        }

        Service::create($validated);

        return redirect()
            ->route('admin.services.index')
            ->with('success', __('messages.service_created'));
    }

    public function edit(Service $service): View
    {
        return view('admin.services.edit', compact('service'));
    }

    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'short_description' => 'required|string',
            'full_description' => 'nullable|string',
            'icon' => 'nullable|string|max:50',
            'features' => 'nullable|string',
            'image_url' => 'nullable|url|max:500',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'image_path' => 'nullable|string',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer',
        ]);

        $validated['slug'] = \Illuminate\Support\Str::slug($validated['title']);
        $validated['features'] = $validated['features'] ? array_map('trim', explode("\n", $validated['features'])) : [];
        $validated['is_active'] = $request->has('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        // Use AJAX uploaded path if provided, otherwise use traditional file upload
        if (!empty($validated['image_path'])) {
            if ($service->image_path && $service->image_path !== $validated['image_path']) {
                Storage::disk('public')->delete($service->image_path);
            }
            $validated['image_url'] = null; // Clear URL if file is uploaded
        } elseif ($request->hasFile('image')) {
            // Delete old image if exists
            if ($service->image_path) {
                Storage::disk('public')->delete($service->image_path);
            }
            
            $imagePath = $request->file('image')->store('services', 'public');
            $validated['image_path'] = $imagePath;
            $validated['image_url'] = null; // Clear URL if file is uploaded
        } else {
            // Keep existing image if no new image provided
            unset($validated['image_path']);
        }

        $service->update($validated);

        return redirect()
            ->route('admin.services.index')
            ->with('success', __('messages.service_updated'));
    }

    public function destroy(Service $service)
    {
        $service->delete();

        return redirect()
            ->route('admin.services.index')
            ->with('success', __('messages.service_deleted'));
    }
}
