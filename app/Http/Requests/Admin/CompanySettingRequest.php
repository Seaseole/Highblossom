<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Trait\CompanyValidationRules;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Validate and authorize company settings updates.
 */
final class CompanySettingRequest extends FormRequest
{
    use CompanyValidationRules;

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
        return $this->validateCompanyRules();
    }
}
