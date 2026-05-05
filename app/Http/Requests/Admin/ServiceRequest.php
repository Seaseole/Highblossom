<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

final class ServiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'short_description' => ['required', 'string'],
            'full_description' => ['nullable', 'string'],
            'icon' => ['nullable', 'string', 'max:50'],
            'features' => ['nullable', 'string'],
            'image_url' => ['nullable', 'url', 'max:500'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'image_path' => ['nullable', 'string'],
            'remove_image' => ['nullable', 'boolean'],
            'is_active' => ['boolean'],
            'sort_order' => ['nullable', 'integer'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_active' => $this->boolean('is_active'),
            'sort_order' => $this->input('sort_order', 0),
        ]);
    }

    public function validatedData(): array
    {
        $validated = $this->validated();

        $validated['slug'] = Str::slug($validated['title']);
        $validated['features'] = $validated['features']
            ? array_map('trim', explode("\n", $validated['features']))
            : [];

        return $validated;
    }
}
