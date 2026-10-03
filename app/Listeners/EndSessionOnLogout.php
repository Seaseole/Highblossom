<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Enums\SessionEndReason;
use App\Services\UserSessionService;
use Illuminate\Auth\Events\Logout;

/**
 * Closes the ledger row when the user signs out.
 *
 * The framework destroys its own session row during `Session::invalidate()`,
 * which happens after this event, so the id is still readable here.
 */
final class EndSessionOnLogout
{
    public function __construct(
        private readonly UserSessionService $sessions,
    ) {}

    /**
     * Handle the event.
     */
    public function handle(Logout $event): void
    {
        $this->sessions->closeCurrentSession(request(), SessionEndReason::LOGOUT);
    }
}
