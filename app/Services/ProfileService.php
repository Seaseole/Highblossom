<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Fortify\Features;

final class ProfileService
{
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

    public function enableTwoFactor(User $user): bool
    {
        if (!Features::canManageTwoFactorAuthentication()) {
            return false;
        }

        $user->forceFill([
            'two_factor_secret' => encrypt('test-secret'),
            'two_factor_confirmed_at' => now(),
        ])->save();

        return true;
    }

    public function disableTwoFactor(User $user): bool
    {
        if (!Features::canManageTwoFactorAuthentication()) {
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
        if (!Hash::check($password, $user->password)) {
            return false;
        }

        Auth::logout();
        $user->delete();

        return true;
    }
}
