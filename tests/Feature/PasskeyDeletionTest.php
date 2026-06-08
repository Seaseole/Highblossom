<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use App\Livewire\Passkeys;

class PasskeyDeletionTest extends TestCase
{
    use RefreshDatabase;

    public function test_passkey_can_be_deleted()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // Create a passkey for the user manually
        $passkeyId = \Illuminate\Support\Facades\DB::table('passkeys')->insertGetId([
            'user_id' => $user->id,
            'name' => 'My Passkey',
            'credential_id' => 'test-credential-id',
            'credential' => json_encode(['public_key' => 'test-public-key']),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Livewire::test(Passkeys::class)
            ->call('deletePasskey', $passkeyId)
            ->assertDontSee('My Passkey');

        $this->assertDatabaseMissing('passkeys', [
            'id' => $passkeyId,
        ]);
    }
}
