<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Validate and authorize image upload requests.
 */
final class ImageUploadRequest extends FormRequest
{
    /**
     * Directories the admin AJAX uploader may write into on the public disk.
     *
     * @var array<int, string>
     */
    private const ALLOWED_FOLDERS = [
        'about-us',
        'avatars',
        'gallery',
        'partners',
        'quotes',
        'services',
        'settings',
        'staff',
        'uploads',
        'uploads/blog',
        'uploads/images',
    ];

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
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg,webp,gif', 'max:2048'], // 2MB max
            'folder' => ['nullable', 'string', Rule::in(self::ALLOWED_FOLDERS)],
        ];
    }
}
