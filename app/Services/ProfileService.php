<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Fortify\Actions\ConfirmTwoFactorAuthentication;
use Laravel\Fortify\Actions\DisableTwoFactorAuthentication;
use Laravel\Fortify\Actions\EnableTwoFactorAuthentication;
use Laravel\Fortify\Actions\GenerateNewRecoveryCodes;
use Laravel\Fortify\Features;

final class ProfileService
{
    public function __construct(
        private readonly EnableTwoFactorAuthentication $enableTwoFactorAction,
        private readonly ConfirmTwoFactorAuthentication $confirmTwoFactorAction,
        private readonly DisableTwoFactorAuthentication $disableTwoFactorAction,
        private readonly GenerateNewRecoveryCodes $generateRecoveryCodesAction,
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

        ($this->enableTwoFactorAction)($user, true);

        return true;
    }

    public function confirmTwoFactor(User $user, string $code): void
    {
        ($this->confirmTwoFactorAction)($user, $code);
    }

    public function disableTwoFactor(User $user): bool
    {
        if (! Features::canManageTwoFactorAuthentication()) {
            return false;
        }

        ($this->disableTwoFactorAction)($user);

        return true;
    }

    public function regenerateRecoveryCodes(User $user): void
    {
        ($this->generateRecoveryCodesAction)($user);
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
