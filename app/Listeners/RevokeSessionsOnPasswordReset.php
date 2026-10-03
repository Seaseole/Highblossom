<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Models\User;
use App\Services\UserSessionService;
use Illuminate\Auth\Events\PasswordReset;

/**
 * Revokes a user's other sessions after a forgotten password has been reset.
 *
 * Fortify rotates the remember token here, which strands remember-me cookies but
 * leaves already-authenticated sessions alive, so an attacker's live session would
 * survive a reset without this.
 */
final class RevokeSessionsOnPasswordReset
{
    public function __construct(
        private readonly UserSessionService $sessions,
    ) {}

    /**
     * Handle the event.
     */
    public function handle(PasswordReset $event): void
    {
        if (! $event->user instanceof User) {
            return;
        }

        $request = request();

        $this->sessions->handlePasswordChanged(
            $event->user,
            $request->hasSession() ? $request->session()->getId() : null,
        );
    }
}
