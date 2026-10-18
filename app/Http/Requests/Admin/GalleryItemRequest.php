<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Models\GalleryImage;
use App\Rules\SafeStoredUploadPath;
use App\Support\SortOrderRules;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Validate and authorize gallery item requests.
 */
final class GalleryItemRequest extends FormRequest
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
            'title' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:500'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:10240'],
            'image_path' => ['nullable', 'string', new SafeStoredUploadPath(['gallery', 'uploads', 'uploads/blog', 'uploads/images', 'uploads/videos', 'uploads/videos/thumbnails'], 'placeholder.gif')],
            'remove_image' => ['nullable', 'boolean'],
            'gallery_category_id' => ['required', 'exists:gallery_categories,id'],
            'is_featured' => ['boolean'],
            'is_active' => ['boolean'],
            // GalleryService forces source=gallery on both save paths, so this form
            // always numbers inside the gallery's own line.
            'sort_order' => SortOrderRules::rules(
                GalleryImage::class,
                array_merge($this->all(), ['source' => GalleryImage::SOURCE_GALLERY]),
                $this->route('item'),
            ),
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'location_address' => ['nullable', 'string', 'max:255'],
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_featured' => $this->boolean('is_featured'),
            'is_active' => $this->boolean('is_active'),
        ]);

        SortOrderRules::normalize($this, GalleryImage::class, $this->route('item'));
    }
}
