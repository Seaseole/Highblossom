<?php

namespace Tests\Feature\Auth;

use App\Models\CompanySetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        CompanySetting::set('enable_registration', '1', 'boolean');
    }

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get(route('register'));

        $response->assertOk();
    }

    public function test_new_users_can_register(): void
    {
        $response = $this->post(route('register.store'), [
            'name' => 'John Doe',
            'email' => 'test@example.com',
            'password' => 'Password!2345',
            'password_confirmation' => 'Password!2345',
            'terms' => '1',
            'privacy' => '1',
        ]);

        $response->assertSessionHasNoErrors()
            ->assertRedirect(route('dashboard', absolute: false));

        $this->assertAuthenticated();
    }

    public function test_registration_screen_is_not_found_when_registration_is_disabled(): void
    {
        CompanySetting::set('enable_registration', '0', 'boolean');

        $this->get(route('register'))->assertNotFound();
    }

    public function test_new_users_cannot_register_when_registration_is_disabled(): void
    {
        CompanySetting::set('enable_registration', '0', 'boolean');

        $this->post(route('register.store'), [
            'name' => 'John Doe',
            'email' => 'test@example.com',
            'password' => 'Password!2345',
            'password_confirmation' => 'Password!2345',
            'terms' => '1',
            'privacy' => '1',
        ])->assertNotFound();

        $this->assertGuest();
    }
}
