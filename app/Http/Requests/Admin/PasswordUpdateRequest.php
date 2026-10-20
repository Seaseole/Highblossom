<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Concerns\PasswordValidationRules;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Validate and authorize password update requests.
 *
 * The strength rule comes from Password::default(), which is the policy the
 * administrator sets under System → Security, so the Security page cannot drift away
 * from the registration and reset forms. It previously carried its own Password::min(8),
 * which let a 8-character password through a form whose hint promised 12.
 */
final class PasswordUpdateRequest extends FormRequest
{
    use PasswordValidationRules;

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
            'current_password' => ['required', 'string'],
            'password' => $this->passwordRules(),
        ];
    }
}
