<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class GlassSubCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $glassSubCategoryId = $this->route('glass_sub_category')?->id;

        return [
            'glass_type_id' => [
                'required',
                'exists:glass_types,id',
            ],
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('glass_sub_categories')->ignore($glassSubCategoryId),
            ],
            'is_active' => [
                'boolean',
            ],
            'sort_order' => [
                'integer',
                'min:0',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'glass_type_id.required' => 'Please select a glass type.',
            'glass_type_id.exists' => 'The selected glass type is invalid.',
            'name.required' => 'The sub-category name is required.',
            'name.max' => 'The sub-category name may not be greater than 255 characters.',
            'slug.unique' => 'The slug must be unique.',
            'sort_order.min' => 'The sort order must be at least 0.',
        ];
    }

    protected function prepareForValidation(): void
    {
        // Convert checkbox to boolean
        if ($this->has('is_active')) {
            $this->merge([
                'is_active' => $this->boolean('is_active'),
            ]);
        }

        // Auto-generate slug from name if not provided
        if (empty($this->slug) && !empty($this->name)) {
            $this->merge([
                'slug' => str($this->name)->slug()->toString(),
            ]);
        }
    }
}
