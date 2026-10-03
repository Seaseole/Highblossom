<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\SvgWriter;
use Illuminate\Http\UploadedFile;
use Illuminate\Image\ImageException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Image;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Laravel\Fortify\Contracts\TwoFactorAuthenticationProvider;
use Laravel\Fortify\Features;
use Laravel\Fortify\RecoveryCode;

final class ProfileService
{
    /** Avatar cover dimensions (square). */
    private const AVATAR_DIMENSIONS = [256, 256];

    /** Output quality for processed images (1-100). */
    private const IMAGE_QUALITY = 82;

    public function __construct(
        protected TwoFactorAuthenticationProvider $provider,
        protected UserSessionService $userSessions,
    ) {}

    public function updateProfile(User $user, array $data, ?UploadedFile $avatar = null): User
    {
        if ($avatar !== null && $avatar->isValid()) {
            $path = $this->storeAvatar($avatar);

            if ($path !== false) {
                $this->deleteStoredImage($user->avatar_path);
                $data['avatar_path'] = $path;
            }
        }

        $user->update($data);

        return $user->fresh();
    }

    /**
     * Process an avatar upload through the Image facade (cover to a square,
     * normalised to WebP) and store it on the public disk.
     */
    private function storeAvatar(UploadedFile $file): string|false
    {
        try {
            return Image::fromUpload($file)
                ->cover(self::AVATAR_DIMENSIONS[0], self::AVATAR_DIMENSIONS[1])
                ->toWebp()
                ->quality(self::IMAGE_QUALITY)
                ->store('avatars', 'public');
        } catch (ImageException $e) {
            Log::error('Failed to process avatar upload: '.$e->getMessage());

            return false;
        }
    }

    /**
     * Delete a stored image from the public disk if it exists.
     */
    private function deleteStoredImage(?string $path): void
    {
        if (blank($path)) {
            return;
        }

        try {
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        } catch (\Exception $e) {
            Log::warning('Could not delete stored avatar image', [
                'path' => $path,
                'reason' => $e->getMessage(),
            ]);
        }
    }

    public function updateAppearance(User $user, array $data): User
    {
        $user->update($data);

        return $user->fresh();
    }

    /**
     * Replace the user's password after verifying the current one, then revoke
     * every other session so a stolen login cannot outlive the password it used.
     *
     * @return bool False when the current password does not match.
     */
    public function updatePassword(User $user, string $currentPassword, string $newPassword): bool
    {
        if (! Hash::check($currentPassword, $user->password)) {
            return false;
        }

        $user->update(['password' => Hash::make($newPassword)]);

        $this->userSessions->handlePasswordChanged($user, session()->getId(), $user);

        return true;
    }

    /**
     * Start two-factor authentication setup for the user.
     *
     * Idempotent: an existing (pending or confirmed) secret is kept so that
     * already-displayed QR codes stay valid and a second request cannot
     * silently invalidate a working setup.
     */
    public function enableTwoFactor(User $user): bool
    {
        if (! Features::canManageTwoFactorAuthentication()) {
            return false;
        }

        if ($user->two_factor_secret) {
            return true;
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

    /**
     * Confirm a pending two-factor authentication setup with a TOTP code.
     */
    public function confirmTwoFactor(User $user, string $code): bool
    {
        if (! Features::canManageTwoFactorAuthentication()) {
            return false;
        }

        $code = str_replace([' ', '-'], '', trim($code));

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

    /**
     * Abandon a pending (unconfirmed) two-factor authentication setup.
     *
     * Refuses to touch a confirmed setup — use disableTwoFactor for that.
     */
    public function cancelTwoFactorSetup(User $user): bool
    {
        if (! Features::canManageTwoFactorAuthentication() || $user->two_factor_confirmed_at) {
            return false;
        }

        $user->forceFill([
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
        ])->save();

        return true;
    }

    /**
     * Determine whether the given password matches the user's current password.
     */
    public function verifyPassword(User $user, string $password): bool
    {
        if ($user->password === null) {
            return false;
        }

        return Hash::check($password, $user->password);
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
