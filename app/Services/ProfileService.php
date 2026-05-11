<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Fortify\Actions\EnableTwoFactorAuthentication;
use Laravel\Fortify\Actions\ConfirmTwoFactorAuthentication;
use Laravel\Fortify\Actions\DisableTwoFactorAuthentication;
use Laravel\Fortify\Contracts\TwoFactorAuthenticationProvider;
use Laravel\Fortify\Features;

final class ProfileService
{
    public function __construct(
        private readonly TwoFactorAuthenticationProvider $twoFactorProvider,
        private readonly EnableTwoFactorAuthentication $enableTwoFactor,
        private readonly ConfirmTwoFactorAuthentication $confirmTwoFactor,
        private readonly DisableTwoFactorAuthentication $disableTwoFactor,
    ) {}
    public function updateProfile(User $user, array $data): User
    {
        $user->update($data);

        return $user->fresh();
    }

    public function updateAppearance(User $user, array $data): User
    {
        $user->update($data);

        return $user->fresh();
    }

    public function updatePassword(User $user, string $currentPassword, string $newPassword): bool
    {
        if (!Hash::check($currentPassword, $user->password)) {
            return false;
        }

        $user->update(['password' => Hash::make($newPassword)]);

        return true;
    }

    public function enableTwoFactor(User $user): array
    {
        if (!Features::canManageTwoFactorAuthentication()) {
            throw new \Exception('Two-factor authentication is not enabled.');
        }

        // Generate TOTP secret and recovery codes using Fortify's action
        ($this->enableTwoFactor)($user);

        $user->refresh();

        return [
            'secret' => decrypt($user->two_factor_secret),
            'qr_code_url' => $this->generateQrCodeUrl($user->email, decrypt($user->two_factor_secret)),
            'recovery_codes' => $this->getRecoveryCodes($user),
        ];
    }

    public function confirmTwoFactor(User $user, string $code): bool
    {
        if (!Features::canManageTwoFactorAuthentication()) {
            return false;
        }

        try {
            ($this->confirmTwoFactor)($user, $code);
            return true;
        } catch (\Illuminate\Validation\ValidationException $e) {
            return false;
        }
    }

    public function disableTwoFactor(User $user): bool
    {
        if (!Features::canManageTwoFactorAuthentication()) {
            return false;
        }

        ($this->disableTwoFactor)($user);

        return true;
    }

    public function generateQrCodeUrl(string $email, string $secret): string
    {
        $appName = config('app.name', 'Highblossom Admin');
        
        return sprintf(
            'otpauth://totp/%s:%s?secret=%s&issuer=%s',
            rawurlencode($appName),
            rawurlencode($email),
            $secret,
            rawurlencode($appName)
        );
    }

    public function getRecoveryCodes(User $user): array
    {
        if (!$user->two_factor_recovery_codes) {
            return [];
        }

        return json_decode(decrypt($user->two_factor_recovery_codes), true);
    }

    public function generateNewRecoveryCodes(User $user): array
    {
        if (!Features::canManageTwoFactorAuthentication()) {
            throw new \Exception('Two-factor authentication is not enabled.');
        }

        ($this->enableTwoFactor)($user, true);

        return $this->getRecoveryCodes($user);
    }

    public function isTwoFactorEnabled(User $user): bool
    {
        return !empty($user->two_factor_secret) && !is_null($user->two_factor_confirmed_at);
    }

    public function deleteAccount(User $user, string $password): bool
    {
        if (!Hash::check($password, $user->password)) {
            return false;
        }

        Auth::logout();
        $user->delete();

        return true;
    }
}
