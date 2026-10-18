<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Models\GlassSubCategory;
use App\Support\SortOrderRules;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Validate and authorize glass sub-category requests.
 */
final class GlassSubCategoryRequest extends FormRequest
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
        $subCategory = $this->route('glassSubCategory');

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
                Rule::unique('glass_sub_categories')->ignore($subCategory),
            ],
            'is_active' => [
                'boolean',
            ],
            'sort_order' => SortOrderRules::rules(GlassSubCategory::class, $this->all(), $subCategory),
        ];
    }

    /**
     * Get custom error messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'glass_type_id.required' => 'Please select a glass type.',
            'glass_type_id.exists' => 'The selected glass type is invalid.',
            'name.required' => 'The sub-category name is required.',
            'name.max' => 'The sub-category name may not be greater than 255 characters.',
            'slug.unique' => 'The slug must be unique.',
            'sort_order.min' => 'The sort order must be at least 1.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Convert checkbox to boolean
        if ($this->has('is_active')) {
            $this->merge([
                'is_active' => $this->boolean('is_active'),
            ]);
        }

        // Auto-generate slug from name if not provided
        if (empty($this->slug) && ! empty($this->name)) {
            $this->merge([
                'slug' => str($this->name)->slug()->toString(),
            ]);
        }

        SortOrderRules::normalize($this, GlassSubCategory::class, $this->route('glassSubCategory'));
    }
}
