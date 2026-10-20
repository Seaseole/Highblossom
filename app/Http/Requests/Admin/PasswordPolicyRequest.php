<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Services\PasswordPolicy;
use Closure;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

/**
 * Validate the password policy form.
 *
 * The bounds are the point of this screen. A policy that accepted a 4-character minimum
 * would weaken every password form in the application through the settings the interface
 * exists to protect, so the floor and ceiling are asserted here as well as clamped again
 * in PasswordPolicy::current() — a value that reaches the database by another route
 * still cannot widen the rule past what bcrypt can hash without truncating.
 */
final class PasswordPolicyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * Route middleware carries `can:update settings`; this mirrors it so the request
     * cannot be reused behind a route that forgot the permission.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('update settings') ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'password_min_length' => ['required', 'integer', 'between:'.PasswordPolicy::MIN_FLOOR.','.PasswordPolicy::MAX_CEILING],
            'password_max_length' => ['required', 'integer', 'between:'.PasswordPolicy::MAX_FLOOR.','.PasswordPolicy::MAX_CEILING],
            'password_require_mixed_case' => ['nullable', 'boolean'],
            'password_require_numbers' => ['nullable', 'boolean'],
            'password_require_symbols' => ['nullable', 'boolean'],
            'password_check_breached' => ['nullable', 'boolean'],
        ];
    }

    /**
     * Names as they read inside a sentence.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'password_min_length' => 'minimum length',
            'password_max_length' => 'maximum length',
        ];
    }

    /**
     * Plain-language messages that name the allowed range instead of the rule.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'password_min_length.required' => 'Enter a minimum password length.',
            'password_min_length.integer' => 'The minimum length must be a whole number of characters.',
            'password_min_length.between' => 'The minimum length must be between '.PasswordPolicy::MIN_FLOOR.' and '.PasswordPolicy::MAX_CEILING.' characters.',
            'password_max_length.required' => 'Enter a maximum password length.',
            'password_max_length.integer' => 'The maximum length must be a whole number of characters.',
            'password_max_length.between' => 'The maximum length must be between '.PasswordPolicy::MAX_FLOOR.' and '.PasswordPolicy::MAX_CEILING.' characters.',
        ];
    }

    /**
     * Reject a range whose ends are crossed.
     *
     * `between` validates each field on its own, so a minimum of 40 against a maximum of 20
     * would pass both rules and produce a policy no password could satisfy.
     *
     * The closure is returned rather than nested in an array: Validator::after() takes the
     * callback and recurses into anything array-shaped by calling ->after() on each element,
     * so an array of rule arrays is a 500 rather than a guard rail.
     *
     * @return Closure(Validator): void
     */
    public function after(): Closure
    {
        return function (Validator $validator): void {
            $max = (int) $this->input('password_max_length');

            if ((int) $this->input('password_min_length') > $max) {
                $validator->errors()->add(
                    'password_min_length',
                    'The minimum length cannot be above the maximum length of '.$max.' characters.'
                );
            }
        };
    }
}
