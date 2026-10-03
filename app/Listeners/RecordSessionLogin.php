<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Enums\LoginMethod;
use App\Models\User;
use Illuminate\Auth\Events\Login;
use Illuminate\Http\Request;

/**
 * Leaves login metadata in the session for the tracking middleware to pick up.
 *
 * The ledger row cannot be written here because the framework regenerates the
 * session id immediately after this event fires; the payload survives that
 * regeneration, so handing the detail over is the reliable route.
 */
final class RecordSessionLogin
{
    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        if (! $event->user instanceof User) {
            return;
        }

        $request = request();

        if (! $request->hasSession()) {
            return;
        }

        $request->session()->put((string) config('user-sessions.handoff_key'), [
            'login_method' => $this->resolveMethod($request, $event->remember)->value,
            'remembered' => $event->remember,
        ]);
    }

    /**
     * Infer how the user signed in from the route that authenticated them.
     */
    private function resolveMethod(Request $request, bool $remembered): LoginMethod
    {
        return match ($request->route()?->getName()) {
            // A two-factor user never reaches this event on the password step,
            // so PASSWORD here means the single-factor path.
            'login.store' => LoginMethod::PASSWORD,
            'two-factor.login.store' => LoginMethod::TWO_FACTOR,
            'passkey.login' => LoginMethod::PASSKEY,
            default => $remembered ? LoginMethod::REMEMBER : LoginMethod::UNKNOWN,
        };
    }
}
