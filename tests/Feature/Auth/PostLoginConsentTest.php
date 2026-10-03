<?php

declare(strict_types=1);

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

/**
 * Coverage for the post-login consent capture gate: unconsented users are
 * blocked from authenticated areas until they accept terms and privacy.
 */
class PostLoginConsentTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Authenticate an admin-panel user with the given consent state.
     */
    private function actingAsPanelUser(bool $consented): User
    {
        Permission::findOrCreate('access admin panel', 'web');

        $user = $consented
            ? User::factory()->create()
            : User::factory()->withoutConsent()->create();
        $user->givePermissionTo('access admin panel');
        $this->actingAs($user);

        return $user;
    }

    public function test_user_without_consent_is_redirected_to_the_consent_page(): void
    {
        $this->actingAsPanelUser(consented: false);

        $this->get(route('dashboard'))
            ->assertRedirect(route('consent'));
    }

    public function test_user_with_consent_passes_the_gate(): void
    {
        $this->actingAsPanelUser(consented: true);

        $this->get(route('dashboard'))->assertOk();
    }

    public function test_consent_page_is_reachable_for_an_unconsented_user(): void
    {
        $this->actingAsPanelUser(consented: false);

        $this->get(route('consent'))
            ->assertOk()
            ->assertSee('name="terms"', false)
            ->assertSee('name="privacy"', false);
    }

    public function test_accepting_both_policies_records_consent_and_continues(): void
    {
        $user = $this->actingAsPanelUser(consented: false);

        $this->get(route('dashboard'));
        $this->post(route('consent.store'), ['terms' => '1', 'privacy' => '1'])
            ->assertRedirect(route('dashboard'));

        $user->refresh();
        $this->assertNotNull($user->terms_accepted_at);
        $this->assertNotNull($user->privacy_accepted_at);

        $this->get(route('dashboard'))->assertOk();
    }

    public function test_accepting_only_terms_fails_validation(): void
    {
        $user = $this->actingAsPanelUser(consented: false);

        $this->post(route('consent.store'), ['terms' => '1'])
            ->assertSessionHasErrors('privacy');

        $this->assertNull($user->fresh()->privacy_accepted_at);
        $this->assertNull($user->fresh()->terms_accepted_at);
    }

    public function test_guest_is_redirected_to_login_not_the_consent_page(): void
    {
        $this->get(route('dashboard'))
            ->assertRedirect(route('login'));
    }

    public function test_json_requests_from_unconsented_users_are_forbidden(): void
    {
        $this->actingAsPanelUser(consented: false);

        $this->getJson(route('dashboard'))->assertForbidden();
    }
}
