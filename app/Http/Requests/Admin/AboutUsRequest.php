<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

/**
 * Validate and authorize about us page data.
 */
final class AboutUsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:500'],
            'body' => ['required', 'string'],
            'hero_image' => ['nullable', 'image', 'max:10240'],
            'hero_image_path' => [
                'nullable',
                'string',
                Rule::in($this->allowedHeroImagePaths()),
            ],
            'mission' => ['nullable', 'string'],
            'vision' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    /**
     * Get a list of valid hero image paths on the public disk.
     *
     * Prevents path traversal and ensures the path actually exists.
     *
     * @return array<int, string>
     */
    private function allowedHeroImagePaths(): array
    {
        $paths = [];

        try {
            $disk = Storage::disk('public');

            // Only allow paths under the about-us folder
            $folder = 'about-us';

            if ($disk->exists($folder)) {
                $files = $disk->files($folder);

                foreach ($files as $file) {
                    // Ensure the path is within the allowed folder
                    if (str_starts_with($file, $folder.'/') && ! str_contains($file, '..')) {
                        $paths[] = $file;
                    }
                }
            }
        } catch (\Exception) {
            // If disk access fails, return empty array to deny all paths
            return [];
        }

        return $paths;
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_active' => $this->boolean('is_active'),
        ]);
    }
}
