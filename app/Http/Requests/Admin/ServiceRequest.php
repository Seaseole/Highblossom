<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Models\Service;
use App\Rules\SafeStoredUploadPath;
use App\Support\SortOrderRules;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

/**
 * Validate and authorize service requests.
 */
final class ServiceRequest extends FormRequest
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
            'short_description' => ['required', 'string'],
            'full_description' => ['nullable', 'string'],
            'features' => ['nullable', 'string'],
            'image_url' => ['nullable', 'url', 'max:500'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'image_path' => ['nullable', 'string', new SafeStoredUploadPath(['services', 'uploads', 'uploads/blog', 'uploads/images', 'uploads/videos', 'uploads/videos/thumbnails'], 'placeholder.gif')],
            'remove_image' => ['nullable', 'boolean'],
            'is_active' => ['boolean'],
            'sort_order' => SortOrderRules::rules(Service::class, $this->all(), $this->route('service')),
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_active' => $this->boolean('is_active'),
        ]);

        SortOrderRules::normalize($this, Service::class, $this->route('service'));
    }

    /**
     * Get the validated data with additional processing.
     *
     * @return array<string, mixed>
     */
    public function validatedData(): array
    {
        $validated = $this->validated();

        $validated['slug'] = Str::slug($validated['title']);
        $validated['features'] = filled($validated['features'] ?? null)
            ? array_map('trim', explode("\n", $validated['features']))
            : [];

        return $validated;
    }
}
