<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Models\User;
use App\Support\Username;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

/**
 * Validate and authorize user creation/update requests.
 */
final class UserRequest extends FormRequest
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
        $userId = $this->route('user')?->id;

        return [
            'name' => ['required', 'string', 'max:255'],
            'username' => Username::rules($userId, required: $this->isMethod('POST')),
            'email' => ['required', 'email', 'max:255', Rule::unique(User::class)->ignore($userId)],
            // The policy is the one every other password form enforces. This request
            // carried a bare min:8, so an admin could hand out a password the site's own
            // registration screen would refuse.
            'password' => [
                $this->isMethod('POST') ? 'required' : 'nullable',
                'string',
                Password::default(),
            ],
            'roles' => ['array'],
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
            'username' => 'username',
        ];
    }

    /**
     * Plain-language messages, so an error states the fix rather than the rule.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'username.required' => 'Give this account a username — it is what they sign in with.',
            'username.min' => 'A username needs at least '.Username::MIN_LENGTH.' characters.',
            'username.max' => 'A username can be at most '.Username::MAX_LENGTH.' characters.',
            'username.regex' => 'Use letters, numbers, dots, underscores or hyphens, starting with a letter or a number.',
            'username.not_in' => 'That name is held back. Pick another.',
            'username.unique' => 'That username is already taken.',
        ];
    }

    /**
     * Lowercase the username before it is validated.
     *
     * `unique` has to compare the spelling the column holds, and on sqlite that comparison
     * is case-sensitive even though MySQL's is not. An empty box stays null rather than
     * becoming an empty string, which is not `nullable`.
     */
    protected function prepareForValidation(): void
    {
        if (is_string($this->input('username'))) {
            $this->merge(['username' => Username::normalize((string) $this->input('username'))]);
        }
    }
}
