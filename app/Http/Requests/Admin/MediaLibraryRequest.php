<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Rules\SafeStoredUploadPath;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Validate and authorize media library upload requests.
 */
final class MediaLibraryRequest extends FormRequest
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
            'category' => ['required', 'string', 'in:automotive,heavy_machinery,fleet,other'],
            'upload' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,gif', 'max:10240'],
            'image_path' => ['nullable', 'string', new SafeStoredUploadPath(['gallery', 'uploads', 'uploads/images', 'uploads/videos', 'uploads/videos/thumbnails'])],
        ];
    }
}
