<?php

declare(strict_types=1);

namespace App\Http\Requests\Auth;

use App\Support\SignInIdentifier;
use App\Support\Username;
use Laravel\Fortify\Fortify;
use Laravel\Fortify\Http\Requests\SendPasswordResetLinkRequest as FortifySendPasswordResetLinkRequest;

/**
 * Forgot-password validation, owned by the application instead of the browser.
 *
 * The field takes either identifier, so a person who only remembers their username is not
 * told their address is malformed. What the broker receives is always an address —
 * prepareForValidation() swaps a username for the email on that account, because the reset
 * link has to go somewhere a mailbox exists. When nothing matches, a value that cannot
 * exist is passed through instead, so the not-found branch runs and is answered with the
 * same sentence as the found one.
 *
 * The form carries `novalidate`, so these sentences are what a typo'd address actually
 * reaches the user with; Fortify's own ("The email must be a valid email address.") read
 * like a framework error next to the friendly copy on the sign-in screen. Same treatment as
 * LoginRequest: keep the constraint, say it in plain language, one sentence per field.
 *
 * Bound over Laravel\Fortify\Http\Requests\SendPasswordResetLinkRequest in
 * FortifyServiceProvider so the broker call and the throttle stay untouched.
 */
class SendPasswordResetLinkRequest extends FortifySendPasswordResetLinkRequest
{
    /**
     * Read the identifier as either form and hand the broker an email address.
     *
     * Runs before validation and before the controller reads only(Fortify::email()), so
     * Fortify's own pipeline is untouched — it simply never sees a username. Only a value
     * that is well-formed gets swapped: rewriting a typo into a placeholder would send it
     * down the not-found branch and answer a mistake the person could fix with the generic
     * "if it matches an account" sentence.
     */
    protected function prepareForValidation(): void
    {
        $identifier = SignInIdentifier::normalize((string) $this->input(Fortify::email()));

        if ($identifier === '' || SignInIdentifier::isEmail($identifier) || ! Username::isValid($identifier)) {
            return;
        }

        // A username that resolves to nothing becomes an address that cannot exist, which
        // keeps the response on the not-found branch. The local part is dropped rather than
        // reused: a username is guessable, and echoing it back through a lookup-shaped
        // string would file it in the reset table's key.
        $this->merge([
            Fortify::email() => SignInIdentifier::find($identifier)?->email
                ?? 'no-such-account.invalid',
        ]);
    }

    /**
     * Get the validation rules that apply to the reset-link request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            Fortify::email() => ['bail', 'required', 'string', 'max:255', function (string $attribute, mixed $value, \Closure $fail): void {
                $value = SignInIdentifier::normalize((string) $value);

                if ($value === '') {
                    $fail('Enter the email address on your account.');

                    return;
                }

                if (SignInIdentifier::isEmail($value)) {
                    return;
                }

                if (! Username::isValid($value)) {
                    $fail('Enter the email address on your account, or your username.');
                }
            }],
        ];
    }

    /**
     * Field names as they read inside a sentence.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            Fortify::email() => 'username or email address',
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
            Fortify::email().'.required' => 'Enter the email address on your account, or your username.',
            Fortify::email().'.max' => 'That username or email address is too long.',
        ];
    }
}
