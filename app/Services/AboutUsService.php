<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\AboutUsContent;
use Illuminate\Container\Attributes\Singleton;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/**
 * Service for managing the About Us page content.
 */
#[Singleton(name: 'about_us')]
final class AboutUsService
{
    /**
     * Get existing content or create with defaults if none exists.
     */
    public function getOrCreateContent(): AboutUsContent
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

        return $content;
    }

    /**
     * Update About Us content with hero image handling.
     */
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

    /**
     * Handle hero image upload, replacement, or removal.
     */
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

        if (! empty($imagePath)) {
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
                Log::error('Failed to store hero image: '.$e->getMessage());
            }
        }
    }
}
