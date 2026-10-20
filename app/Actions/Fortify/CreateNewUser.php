<?php

declare(strict_types=1);

namespace App\Actions\Fortify;

use App\Concerns\PasswordValidationRules;
use App\Concerns\ProfileValidationRules;
use App\Models\User;
use App\Support\Username;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;

/**
 * Validate and create a newly registered user.
 *
 * Implements Laravel Fortify's CreatesNewUsers contract
 * using the shared password and profile validation rules.
 */
class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules, ProfileValidationRules;

    /**
     * Field names as they read inside a sentence.
     *
     * @var array<string, string>
     */
    private const ATTRIBUTES = [
        'name' => 'full name',
        'username' => 'username',
        'email' => 'email address',
        'password' => 'password',
        'terms' => 'terms',
        'privacy' => 'privacy policy',
    ];

    /**
     * Plain-language messages, so each error names the fix.
     *
     * Deliberately does not override the Password::default() rules: that strength
     * rule reports its own list of unmet requirements, and AuthNotificationBannerTest
     * pins the count of those sentences.
     *
     * `username.required` is absent on purpose — this action only ever runs behind
     * Fortify's registered route, whose request has no rules array, so a missing name has
     * to fall through to Laravel's sentence rather than a localised key that would then
     * have to match the field hint word for word.
     *
     * @var array<string, string>
     */
    private const MESSAGES = [
        'name.required' => 'Enter your full name.',
        'username.min' => 'A username needs at least '.Username::MIN_LENGTH.' characters.',
        'username.max' => 'A username can be at most '.Username::MAX_LENGTH.' characters.',
        'username.regex' => 'Use letters, numbers, dots, underscores or hyphens, starting with a letter or a number.',
        'username.not_in' => 'That name is held back. Pick another.',
        'username.unique' => 'That username is taken.',
        'email.required' => 'Enter your email address.',
        'email.email' => 'Enter a complete email address, like name@example.com.',
        'email.unique' => 'That email address already has an account — sign in, or reset the password.',
        'password.required' => 'Choose a password.',
        'password.confirmed' => 'The two passwords don’t match. Retype it in both boxes.',
        'terms.required' => 'Tick the terms box to continue.',
        'terms.accepted' => 'Tick the terms box to continue.',
        'privacy.required' => 'Tick the privacy policy box to continue.',
        'privacy.accepted' => 'Tick the privacy policy box to continue.',
    ];

    /**
     * Validate and create a newly registered user.
     *
     * @param array<string, string> $input
     */
    public function create(array $input): User
    {
        // Normalised before it is validated, not only before it is stored: `unique` has to
        // compare the same spelling the column holds, and on sqlite — which the suite runs
        // on — that comparison is case-sensitive even though MySQL's is not. Without this,
        // "Eugene" would pass as free against a stored "eugene" and then collide on insert.
        $input['username'] = Username::normalize($input['username'] ?? null);

        Validator::make($input, [
            ...$this->profileRules(),
            'password' => $this->passwordRules(),
            'terms' => ['required', 'accepted'],
            'privacy' => ['required', 'accepted'],
        ], self::MESSAGES, self::ATTRIBUTES)->validate();

        return User::create([
            'name' => $input['name'],
            'username' => $input['username'],
            'email' => $input['email'],
            'password' => $input['password'],
            'terms_accepted_at' => now(),
            'privacy_accepted_at' => now(),
        ]);
    }
}
