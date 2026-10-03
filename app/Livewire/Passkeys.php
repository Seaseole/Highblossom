<?php

declare(strict_types=1);

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Laravel\Passkeys\Actions\DeletePasskey;
use Laravel\Passkeys\Passkey;
use Livewire\Attributes\On;
use Livewire\Component;

/**
 * Manage WebAuthn passkeys for the authenticated user.
 */
class Passkeys extends Component
{
    #[On('passkeyRegistered')]
    #[On('passkeyDeleted')]
    #[On('passkeyRenamed')]
    /**
     * Refresh the passkey list after a registration, deletion, or rename event.
     */
    public function update()
    {
        // Refresh trigger
    }

    /**
     * Delete a passkey after verifying it belongs to the authenticated user.
     */
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
    /**
     * Handle a rename request dispatched from the frontend.
     */
    public function handleRenamePasskey(int $id, string $name)
    {
        $this->renamePasskey($id, $name);
    }

    /**
     * Rename a passkey after verifying ownership.
     */
    public function renamePasskey(int $passkeyId, string $newName)
    {
        $newName = trim($newName);

        if ($newName === '' || mb_strlen($newName) > 255) {
            $this->dispatch('toast', [
                'type' => 'error',
                'message' => 'Passkey name must be between 1 and 255 characters.',
            ]);

            return;
        }

        $passkey = Passkey::find($passkeyId);

        if (! $passkey) {
            $this->dispatch('toast', [
                'type' => 'error',
                'message' => 'Passkey not found. Refresh the page and try again.',
            ]);

            return;
        }

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

    /**
     * Render the passkey management component.
     *
     * @return View
     */
    public function render()
    {
        $passkeys = Passkey::where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('livewire.passkeys', ['passkeys' => $passkeys]);
    }
}
