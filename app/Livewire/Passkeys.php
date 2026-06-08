<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Laravel\Passkeys\Actions\DeletePasskey;
use Laravel\Passkeys\Passkey;

class Passkeys extends Component
{
    public function deletePasskey($passkeyId, DeletePasskey $deletePasskey)
    {
        $passkey = Passkey::findOrFail($passkeyId);

        // Ensure the passkey belongs to the authenticated user
        if ($passkey->user_id !== Auth::id()) {
            $this->dispatch('toast', [
                'type' => 'error',
                'message' => 'Unauthorized action.'
            ]);
            return;
        }

        $deletePasskey(Auth::user(), $passkey);

        $this->dispatch('toast', [
            'type' => 'success',
            'message' => 'Passkey deleted successfully.'
        ]);
    }

    public function render()
    {
        $passkeys = DB::table('passkeys')
            ->where('user_id', Auth::id())
            ->get();

        return view('livewire.passkeys', ['passkeys' => $passkeys]);
    }
}
