<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Laravel\Passkeys\Exceptions\InvalidPasskeyException;
use Laravel\Passkeys\Passkeys;

/**
 * Classify a rejected passkey login into a stable, machine-readable reason.
 *
 * The browser passkey client only forwards the top-level JSON "message", so the
 * login screen needs a reason code it can branch on instead of comparing English
 * strings thrown by the package.
 */
class ResolvePasskeyLoginFailure
{
    /**
     * Session flag marking that a device offered a passkey the account no longer has.
     */
    public const REENROLL_SESSION_KEY = 'passkey_reenroll_needed';

    /**
     * The credential the device signed with is absent from the passkeys table.
     */
    public const REASON_UNRECOGNIZED = 'unrecognized_passkey';

    /**
     * The WebAuthn challenge stored in the session is gone or unreadable.
     */
    public const REASON_EXPIRED_SESSION = 'expired_passkey_session';

    /**
     * The credential is known but the assertion itself was rejected.
     */
    public const REASON_FAILED = 'passkey_verification_failed';

    /**
     * Resolve why the passkey login attempt was rejected.
     *
     * @return array{reason: string, message: string}|null
     */
    public function __invoke(Request $request, ValidationException $exception): ?array
    {
        if ($request->path() !== 'passkeys/login') {
            return null;
        }

        $reason = $this->resolveReason($request, $exception);

        if ($reason === self::REASON_UNRECOGNIZED) {
            $request->session()->put(self::REENROLL_SESSION_KEY, true);
        }

        return [
            'reason' => $reason,
            'message' => __("auth.login.passkey.recovery.{$reason}.message"),
        ];
    }

    /**
     * Determine the reason code for the failure.
     */
    private function resolveReason(Request $request, ValidationException $exception): string
    {
        if ($this->credentialIsUnknown($request)) {
            return self::REASON_UNRECOGNIZED;
        }

        return $exception instanceof InvalidPasskeyException
            ? self::REASON_FAILED
            : self::REASON_EXPIRED_SESSION;
    }

    /**
     * Check whether the credential the browser signed with is absent from the database.
     */
    private function credentialIsUnknown(Request $request): bool
    {
        $rawId = $request->input('credential.rawId');

        if (! is_string($rawId) || $rawId === '') {
            return false;
        }

        /** @var class-string<Model> $model */
        $model = Passkeys::passkeyModel();

        return ! $model::where('credential_id', rtrim($rawId, '='))->exists();
    }
}
