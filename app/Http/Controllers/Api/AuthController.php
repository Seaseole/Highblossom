<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LoginRequest;
use App\Http\Requests\Api\TwoFactorChallengeRequest;
use App\Http\Resources\Api\UserResource;
use App\Models\User;
use App\Services\StaffTwoFactorChallengeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/**
 * Issue and revoke Sanctum tokens for the staff mobile app.
 *
 * Authorization stays with Spatie permissions: tokens carry the wildcard ability and
 * every data route is gated by the same `can:` middleware the web admin uses, so the
 * app can never reach an endpoint the signed-in member could not reach in the browser.
 */
final class AuthController extends Controller
{
    /**
     * Issue a token once both factors are satisfied.
     */
    public function __construct(private readonly StaffTwoFactorChallengeService $challenges) {}

    /**
     * Exchange email/password for a personal access token.
     *
     * When the account has two-factor authentication enabled this returns a
     * challenge instead of a token, so the mobile app cannot become a route
     * around the second factor the web admin insists on.
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $user = User::where('email', $request->string('email')->toString())->first();

        if ($user === null || ! Hash::check($request->string('password')->toString(), $user->password)) {
            return response()->json([
                'message' => 'These credentials do not match our records.',
            ], 422);
        }

        if ($user->cannot('access admin panel')) {
            return response()->json([
                'message' => 'This account is not permitted to use the staff app.',
            ], 403);
        }

        if ($user->hasEnabledTwoFactorAuthentication()) {
            return response()->json([
                'two_factor' => true,
                'challenge' => $this->challenges->issue($user),
            ]);
        }

        return $this->tokenResponse($user, $request->string('device_name')->toString());
    }

    /**
     * Complete a two-factor challenge and issue the token.
     */
    public function twoFactorChallenge(TwoFactorChallengeRequest $request): JsonResponse
    {
        $challenge = $request->string('challenge')->toString();
        $user = $this->challenges->userFor($challenge);

        if ($user === null) {
            return response()->json([
                'message' => 'That sign-in attempt has expired. Sign in again.',
                'reason' => 'challenge_expired',
            ], 422);
        }

        $verified = $this->challenges->verify(
            $challenge,
            $request->string('code')->toString() ?: null,
            $request->string('recovery_code')->toString() ?: null,
        );

        if (! $verified) {
            return response()->json([
                'message' => 'That code is not valid. Check your authenticator, or use a recovery code.',
            ], 422);
        }

        return $this->tokenResponse($user, 'staff-app');
    }

    /**
     * Mint the personal access token and shape the shared success payload.
     *
     * @param User   $user       The authenticated account
     * @param string $deviceName Label stored against the token
     */
    private function tokenResponse(User $user, string $deviceName): JsonResponse
    {
        $token = $user->createToken(
            $deviceName !== '' ? $deviceName : 'staff-app',
            ['*'],
            now()->addMonths(3),
        );

        return response()->json([
            'token' => $token->plainTextToken,
            'user' => new UserResource($user),
        ]);
    }

    /**
     * Return the authenticated member and the abilities the app should gate its UI on.
     */
    public function me(Request $request): JsonResponse
    {
        return response()->json([
            'user' => new UserResource($request->user()),
        ]);
    }

    /**
     * Revoke the token used for the current request.
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out.',
        ]);
    }
}
