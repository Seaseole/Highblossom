<?php

namespace Tests\Feature;

use App\Livewire\Passkeys;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;
use Tests\TestCase;

class PasskeyDeletionTest extends TestCase
{
    use RefreshDatabase;

    public function test_passkey_can_be_deleted()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // Create a passkey for the user manually
        $passkeyId = DB::table('passkeys')->insertGetId([
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
