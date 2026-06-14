<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravel\Passkeys\Actions\DeletePasskey;
use Laravel\Passkeys\Passkey;
use Livewire\Attributes\On;
use Livewire\Component;

class Passkeys extends Component
{
    #[On('passkeyRegistered')]
    #[On('passkeyDeleted')]
    #[On('passkeyRenamed')]
    public function update()
    {
        // Refresh trigger
    }

    public function deletePasskey(Passkey $passkey, DeletePasskey $deletePasskey)
    {
        // Ensure the passkey belongs to the authenticated user
        if ($passkey->user_id !== Auth::id()) {
            $this->dispatch('toast', [
                'type' => 'error',
                'message' => 'Unauthorized action.',
            ]);

            return;
        }

        $deletePasskey(Auth::user(), $passkey);
        $this->dispatch('passkeyDeleted');

        $this->dispatch('toast', [
            'type' => 'success',
            'message' => 'Passkey deleted successfully.',
        ]);
    }

    #[On('renamePasskeyRequest')]
    public function handleRenamePasskey($id, $name)
    {
        $this->renamePasskey($id, $name);
    }

    public function renamePasskey($passkeyId, $newName)
    {
        $passkey = Passkey::findOrFail($passkeyId);

        // Ensure the passkey belongs to the authenticated user
        if ($passkey->user_id !== Auth::id()) {
            $this->dispatch('toast', [
                'type' => 'error',
                'message' => 'Unauthorized action.',
            ]);

            return;
        }

        $passkey->update(['name' => $newName]);

        $this->dispatch('passkeyRenamed');

        $this->dispatch('toast', [
            'type' => 'success',
            'message' => 'Passkey renamed successfully.',
        ]);
    }

    public function render()
    {
        $passkeys = Passkey::where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('livewire.passkeys', ['passkeys' => $passkeys]);
    }
}
