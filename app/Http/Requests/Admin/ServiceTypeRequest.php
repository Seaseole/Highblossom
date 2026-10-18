<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Models\ServiceType;
use App\Support\SortOrderRules;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Validate and authorize service type requests.
 */
final class ServiceTypeRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'sort_order' => SortOrderRules::rules(ServiceType::class, $this->all(), $this->route('serviceType')),
            'is_active' => ['boolean'],
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

        SortOrderRules::normalize($this, ServiceType::class, $this->route('serviceType'));
    }
}
