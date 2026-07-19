<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

/**
 * Validate and authorize quote form submissions.
 */
class QuoteFormRequest extends FormRequest
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
            'image_path' => [
                'nullable',
                'string',
                Rule::in($this->allowedQuoteImagePaths()),
            ],
            'mobile_service' => 'nullable|boolean',
        ];
    }

    /**
     * Get a list of valid quote image paths on the public disk.
     *
     * Prevents path traversal and ensures the path actually exists.
     *
     * @return array<int, string>
     */
    private function allowedQuoteImagePaths(): array
    {
        $paths = [];

        try {
            $disk = Storage::disk('public');

            // Only allow paths under the quotes folder
            $folder = 'quotes';

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
     * Get custom error messages for validator errors.
     *
     * @return array<string, string>
     */
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
