<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

final class MediaLibraryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'in:automotive,heavy_machinery,fleet,other'],
            'upload' => ['nullable', 'image'],
            'image_path' => ['nullable', 'string'],
        ];
    }
}
