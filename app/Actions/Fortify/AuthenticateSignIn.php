<?php

declare(strict_types=1);

namespace App\Actions\Fortify;

use App\Models\User;
use App\Support\SignInIdentifier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Laravel\Fortify\Fortify;
use Throwable;

/**
 * Resolve the single sign-in field and check the password against it.
 *
 * Registered as Fortify's authenticateUsing callback, which replaces the guard's own
 * credential attempt for both the plain login and the two-factor challenge, so the two
 * cannot diverge on what an identifier means.
 *
 * Fortify's custom-callback path hands back a user and calls `login()` rather than
 * `attempt()`, which means two things the default pipeline would have done no longer happen
 * on their own: the password has to be verified here, and so does rehash-on-login. That
 * second one matters more than it looks — the local administrator account was hashed at
 * bcrypt cost 10 while the application now runs at 12, and without this the only moment
 * that hash would ever have been upgraded is the moment it stopped being reached.
 */
final class AuthenticateSignIn
{
    /**
     * Attempt to authenticate the request's credentials.
     *
     * The field is still called `email` on the wire because that is what
     * config/fortify.php declares as the username column; it accepts either identifier.
     */
    public function handle(Request $request): ?User
    {
        $identifier = (string) $request->input(Fortify::username());
        $password = (string) $request->input('password');

        [$valid, $user] = SignInIdentifier::verify($identifier, $password);

        if (! $valid || $user === null) {
            return null;
        }

        $this->rehashIfStale($user, $password);

        return $user;
    }

    /**
     * Bring a password hashed under older bcrypt options up to the current ones.
     *
     * Needs the plain password, not the stored hash — the point is to re-encode what the
     * person just typed. Mirrors config('hashing.rehash_on_login'), which the framework
     * defaults to true and which this application has never set. A failure here must not
     * refuse a sign-in that was just verified, so the upgrade is logged rather than thrown.
     */
    private function rehashIfStale(User $user, string $password): void
    {
        if (! config('hashing.rehash_on_login', true) || ! Hash::needsRehash($user->password)) {
            return;
        }

        try {
            $user->forceFill(['password' => Hash::make($password)])->save();
        } catch (Throwable $e) {
            Log::warning('Password could not be rehashed at sign-in.', [
                'user_id' => $user->getKey(),
                'reason' => $e->getMessage(),
            ]);
        }
    }
}
