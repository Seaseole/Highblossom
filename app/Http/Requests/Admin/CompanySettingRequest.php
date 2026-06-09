<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Trait\CompanyValidationRules;
use Illuminate\Foundation\Http\FormRequest;

final class CompanySettingRequest extends FormRequest
{
    use CompanyValidationRules;
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return $this->ValidationRules();
    }
}
