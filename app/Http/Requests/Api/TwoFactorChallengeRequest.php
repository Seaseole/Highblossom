<?php

declare(strict_types=1);

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

/**
 * Validate the second-factor step of a staff app sign-in.
 *
 * Exactly one of `code` (authenticator) or `recovery_code` must be present;
 * the app sends the six digits, or the one-time recovery code when the device
 * is not producing codes.
 */
final class TwoFactorChallengeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * The challenge id itself is the proof of a completed first factor;
     * authorization is enforced in the controller when it is resolved.
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
            'challenge' => ['required', 'string', 'uuid'],
            'code' => ['nullable', 'string', 'digits_between:6,8'],
            'recovery_code' => ['nullable', 'string', 'max:64'],
        ];
    }

    /**
     * Require one of the two supported second factors.
     *
     * @param Validator $validator The validator instance
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            $filled = array_filter([
                'code' => $this->input('code'),
                'recovery_code' => $this->input('recovery_code'),
            ]);

            if ($filled === []) {
                $validator->errors()->add(
                    'code',
                    'Enter the six-digit code from your authenticator, or use a recovery code.',
                );
            }
        });
    }
}
