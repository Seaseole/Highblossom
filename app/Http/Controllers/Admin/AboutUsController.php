<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Domains\Content\Models\AboutUsContent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

final class AboutUsController
{
    public function edit(): View
    {
        $content = AboutUsContent::first();

        if (! $content) {
            $content = AboutUsContent::create([
                'title' => 'About Highblossom',
                'subtitle' => '',
                'body' => '',
                'mission' => '',
                'vision' => '',
                'is_active' => true,
            ]);
        }

        return view('admin.about-us.edit', compact('content'));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:500',
            'body' => 'required|string',
            'hero_image' => 'nullable|image|max:2048',
            'hero_image_path' => 'nullable|string',
            'mission' => 'nullable|string',
            'vision' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $content = AboutUsContent::first();

        if (! $content) {
            $content = new AboutUsContent();
        }

        $content->title = $validated['title'];
        $content->subtitle = $validated['subtitle'] ?? '';
        $content->body = $validated['body'];
        $content->mission = $validated['mission'] ?? '';
        $content->vision = $validated['vision'] ?? '';
        $content->is_active = $request->boolean('is_active', false);

        // Use AJAX uploaded path if provided, otherwise use traditional file upload
        if (!empty($validated['hero_image_path'])) {
            if ($content->hero_image && $content->hero_image !== $validated['hero_image_path']) {
                Storage::disk('public')->delete($content->hero_image);
            }
            $content->hero_image = $validated['hero_image_path'];
        } elseif ($request->hasFile('hero_image')) {
            try {
                $file = $request->file('hero_image');
                if ($file && $file->isValid()) {
                    if ($content->hero_image) {
                        Storage::disk('public')->delete($content->hero_image);
                    }
                    $content->hero_image = $file->store('about-us', 'public');
                }
            } catch (\Exception $e) {
                \Log::error('Failed to store hero image: ' . $e->getMessage());
                // Continue without updating the image if upload fails
            }
        }

        $content->save();

        return redirect()->back()->with('success', 'About Us content updated successfully.');
    }
}
