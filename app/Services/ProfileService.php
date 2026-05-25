<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\SvgWriter;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Fortify\Contracts\TwoFactorAuthenticationProvider;
use Laravel\Fortify\Features;
use Laravel\Fortify\RecoveryCode;

final class ProfileService
{
    public function __construct(
        protected TwoFactorAuthenticationProvider $provider
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
        if (! Hash::check($currentPassword, $user->password)) {
            return false;
        }

        $user->update(['password' => Hash::make($newPassword)]);

        return true;
    }

    public function enableTwoFactor(User $user): bool
    {
        if (! Features::canManageTwoFactorAuthentication()) {
            return false;
        }

        $user->forceFill([
            'two_factor_secret' => encrypt($this->provider->generateSecretKey()),
            'two_factor_recovery_codes' => encrypt(json_encode(Collection::times(8, function () {
                return RecoveryCode::generate();
            })->all())),
            'two_factor_confirmed_at' => null,
        ])->save();

        return true;
    }

    public function confirmTwoFactor(User $user, string $code): bool
    {
        if (! Features::canManageTwoFactorAuthentication()) {
            return false;
        }

        if (! $user->two_factor_secret ||
            ! $this->provider->verify(decrypt($user->two_factor_secret), $code)) {
            return false;
        }

        $user->forceFill([
            'two_factor_confirmed_at' => now(),
        ])->save();

        return true;
    }

    public function getTwoFactorQrCodeSvg(User $user): string
    {
        $url = $user->twoFactorQrCodeUrl();

        $builder = new Builder(
            writer: new SvgWriter,
            writerOptions: [],
            data: $url,
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::High,
            size: 200,
            margin: 10,
            roundBlockSizeMode: RoundBlockSizeMode::Margin
        );

        return $builder->build()->getString();
    }

    public function regenerateRecoveryCodes(User $user): void
    {
        $user->forceFill([
            'two_factor_recovery_codes' => encrypt(json_encode(Collection::times(8, function () {
                return RecoveryCode::generate();
            })->all())),
        ])->save();
    }

    public function disableTwoFactor(User $user): bool
    {
        if (! Features::canManageTwoFactorAuthentication()) {
            return false;
        }

        $user->forceFill([
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at' => null,
        ])->save();

        return true;
    }

    public function deleteAccount(User $user, string $password): bool
    {
        if (! Hash::check($password, $user->password)) {
            return false;
        }

        Auth::logout();
        $user->delete();

        return true;
    }
}
