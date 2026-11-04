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
    /**
     * Refresh the passkey list after a registration.
     *
     * Deletion and rename refresh on their own: they are method calls on this component, and
     * Livewire renders the response. Only registration arrives from outside — the WebAuthn ceremony
     * is the passkeys package's own fetch — so it is the only one that has to be announced.
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

        /* No `passkeyDeleted` echo. This component is the only listener, and Livewire already
           renders it once for the action that was just called — so the round trip was a second
           render of the same list, and a row that visibly flickered twice on its way out. */
        $this->dispatch('toast', [
            'type' => 'success',
            'message' => 'Passkey removed.',
        ]);
    }

    #[On('renamePasskeyRequest')]
    /**
     * Handle a rename request dispatched from the frontend.
     *
     * The id arrives through a `dataset` read in the view, which is always a string.
     */
    public function handleRenamePasskey(string|int $id, string $name)
    {
        $this->renamePasskey((int) $id, $name);
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
                'message' => 'A passkey needs a name, and 255 characters is the most it can hold.',
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

        $this->dispatch('toast', [
            'type' => 'success',
            'message' => 'Passkey renamed.',
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
