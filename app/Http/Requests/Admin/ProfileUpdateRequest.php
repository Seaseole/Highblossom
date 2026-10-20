<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Models\User;
use App\Support\Username;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Validate and authorize profile update requests.
 */
final class ProfileUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Lowercase the username before it is validated.
     *
     * `unique` has to compare the same spelling the column holds; on sqlite that
     * comparison is case-sensitive even though MySQL's is not, so "Eugene" would read as
     * free against a stored "eugene" and then collide on insert. An empty box is left as
     * null rather than normalised into an empty string, because null satisfies `nullable`
     * and an empty string does not.
     */
    protected function prepareForValidation(): void
    {
        if (is_string($this->input('username'))) {
            $this->merge(['username' => Username::normalize((string) $this->input('username'))]);
        }
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
            // Optional here: this one endpoint saves name, email, phone and avatar together,
            // so a mandatory username would block a phone-number change on an account the
            // backfill has not reached, for a reason the form cannot explain.
            'username' => Username::rules(auth()->id(), required: false),
            'email' => ['required', 'email', 'max:255', Rule::unique(User::class)->ignore(auth()->id())],
            'phone' => ['nullable', 'string', 'max:20', 'regex:/^[0-9+()\s-]+$/'],
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
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
            'username.min' => 'A username needs at least '.Username::MIN_LENGTH.' characters.',
            'username.max' => 'A username can be at most '.Username::MAX_LENGTH.' characters.',
            'username.regex' => 'Use letters, numbers, dots, underscores or hyphens, starting with a letter or a number.',
            'username.not_in' => 'That name is held back. Pick another.',
            'username.unique' => 'That username is already taken.',
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
}
