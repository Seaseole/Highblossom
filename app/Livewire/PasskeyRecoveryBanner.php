<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Actions\Auth\ResolvePasskeyLoginFailure;
use Illuminate\View\View;
use Livewire\Component;

/**
 * Invite a user to re-create a passkey after a rejected passkey sign-in.
 */
class PasskeyRecoveryBanner extends Component
{
    /**
     * Whether the re-enrolment prompt is on screen.
     */
    public bool $visible = false;

    /**
     * Show the prompt when the last passkey sign-in was rejected as unrecognized.
     */
    public function mount(): void
    {
        $this->visible = (bool) session(ResolvePasskeyLoginFailure::REENROLL_SESSION_KEY);
    }

    /**
     * Dismiss the prompt for good.
     */
    public function dismiss(): void
    {
        session()->forget(ResolvePasskeyLoginFailure::REENROLL_SESSION_KEY);

        $this->visible = false;
    }

    /**
     * Render the re-enrolment prompt.
     *
     * @return View
     */
    public function render()
    {
        return view('livewire.passkey-recovery-banner');
    }
}
