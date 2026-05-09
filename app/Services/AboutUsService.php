<?php

declare(strict_types=1);

namespace App\Services;

use App\Domains\Content\Models\AboutUsContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

final class AboutUsService
{
    public function getOrCreateContent(): AboutUsContent
    {
        $content = AboutUsContent::first();

        if (!$content) {
            $content = AboutUsContent::create([
                'title' => 'About Highblossom',
                'subtitle' => '',
                'body' => '',
                'mission' => '',
                'vision' => '',
                'is_active' => true,
            ]);
        }

        return $content;
    }

    public function update(array $data, Request $request): AboutUsContent
    {
        $content = $this->getOrCreateContent();

        $content->title = $data['title'];
        $content->subtitle = $data['subtitle'] ?? '';
        $content->body = $data['body'];
        $content->mission = $data['mission'] ?? '';
        $content->vision = $data['vision'] ?? '';
        $content->is_active = $data['is_active'] ?? false;

        $this->handleHeroImage($content, $request);

        $content->save();

        return $content;
    }

    private function handleHeroImage(AboutUsContent $content, Request $request): void
    {
        // Handle removal request first
        if ($request->boolean('remove_hero_image')) {
            if ($content->hero_image) {
                Storage::disk('public')->delete($content->hero_image);
            }
            $content->hero_image = null;

            return;
        }

        $imagePath = $request->input('hero_image_path');

        if (!empty($imagePath)) {
            if ($content->hero_image && $content->hero_image !== $imagePath) {
                Storage::disk('public')->delete($content->hero_image);
            }
            $content->hero_image = $imagePath;
            return;
        }

        if ($request->hasFile('hero_image')) {
            try {
                $file = $request->file('hero_image');
                if ($file && $file->isValid()) {
                    if ($content->hero_image) {
                        Storage::disk('public')->delete($content->hero_image);
                    }
                    $content->hero_image = $file->store('about-us', 'public');
                }
            } catch (\Exception $e) {
                Log::error('Failed to store hero image: ' . $e->getMessage());
            }
        }
    }
}
