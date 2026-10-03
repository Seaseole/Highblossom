<?php

declare(strict_types=1);

namespace Tests\Feature\Auth;

use App\Actions\Fortify\CreateNewUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

/**
 * Coverage for terms and privacy consent recording during registration.
 */
class RegistrationConsentTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A full, valid registration input with both consent boxes ticked.
     *
     * @return array<string, string>
     */
    private function input(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'Password!2345',
            'password_confirmation' => 'Password!2345',
            'terms' => '1',
            'privacy' => '1',
        ], $overrides);
    }

    public function test_registration_records_consent_timestamps(): void
    {
        $user = (new CreateNewUser)->create($this->input());

        $this->assertNotNull($user->terms_accepted_at);
        $this->assertNotNull($user->privacy_accepted_at);
    }

    public function test_registration_is_rejected_without_terms_consent(): void
    {
        $this->expectException(ValidationException::class);

        (new CreateNewUser)->create($this->input(['terms' => 'no']));
    }

    public function test_registration_is_rejected_without_privacy_consent(): void
    {
        $this->expectException(ValidationException::class);

        (new CreateNewUser)->create($this->input(['privacy' => 'no']));
    }

    public function test_registration_is_rejected_when_consent_is_absent(): void
    {
        $this->expectException(ValidationException::class);

        (new CreateNewUser)->create($this->input(['terms' => '', 'privacy' => '']));
    }
}
