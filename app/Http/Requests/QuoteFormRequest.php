<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuoteFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|nullable|email|max:255',
            'vehicle_type' => 'required|string|in:sedan,suv,truck,van,heavy,fleet,other',
            'make_model' => 'nullable|string|max:255',
            'reg_number' => 'nullable|string|max:20',
            'year' => 'nullable|integer|min:1980|max:'.(date('Y') + 1),
            'glass_type_id' => 'required|exists:glass_types,id',
            'glass_sub_category_id' => 'nullable|exists:glass_sub_categories,id',
            'service_type_id' => 'required|exists:service_types,id',
            'image' => 'nullable|image|max:10240', // Max 10MB
            'image_path' => 'nullable|string|max:255',
            'mobile_service' => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'glass_type_id.required' => 'Please select a glass type',
            'glass_type_id.exists' => 'Invalid glass type selected',
            'glass_sub_category_id.exists' => 'Invalid glass sub-category selected',
            'service_type_id.required' => 'Please select a service type',
            'service_type_id.exists' => 'Invalid service type selected',
            'vehicle_type.in' => 'Please select a valid vehicle type',
        ];
    }
}
