<?php

declare(strict_types=1);

namespace App\Http\Requests\Auth;

use App\Support\SignInIdentifier;
use Laravel\Fortify\Fortify;
use Laravel\Fortify\Http\Requests\LoginRequest as FortifyLoginRequest;

/**
 * Login form validation, owned by the application instead of the browser.
 *
 * The one field takes a username or an email address, and the shape check happens here
 * rather than in the browser: a half-typed address ("eugene@") is answered with a hint the
 * person can act on, while anything that is well-formed but unknown is answered by
 * Fortify's neutral `auth.failed`. That split is what keeps the form from confirming that
 * an account exists — see SignInIdentifier, which also pays for a throwaway bcrypt
 * comparison when the identifier resolves to nobody, so an unknown name and a wrong
 * password take the same time to reject.
 *
 * The field keeps its `email` name on the wire because config/fortify.php still declares
 * `username => email`, and Fortify's pipeline (throttle, canonicalised lookup, session
 * regeneration) is inherited untouched.
 *
 * `bail` keeps one message per field: without it a blank identifier fails `required` and
 * the shape rule in the same pass and the field shows two sentences about one problem.
 *
 * Bound over Laravel\Fortify\Http\Requests\LoginRequest in FortifyServiceProvider.
 */
class LoginRequest extends FortifyLoginRequest
{
    /**
     * Get the validation rules that apply to the sign-in attempt.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return SignInIdentifier::rules();
    }

    /**
     * Field names as they read inside a sentence.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            Fortify::username() => 'username or email address',
            'password' => 'password',
        ];
    }

    /**
     * Plain-language messages, so an error states the fix rather than the rule.
     *
     * `remember` gets no rule and no message on purpose. It is a checkbox, so the browser
     * can only ever send `on`, `1`, or nothing — there is no mistake there a sentence could
     * correct, and Laravel's `boolean` rule is strict enough to reject the browser's own `on`
     * default, which would fail a perfectly valid sign-in attempt.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            Fortify::username().'.required' => 'Enter your username or email address.',
            Fortify::username().'.max' => 'That username or email address is too long.',
            'password.required' => 'Enter your password.',
        ];
    }
}
