<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AccountDeleteRequest;
use App\Http\Requests\Admin\AppearanceRequest;
use App\Http\Requests\Admin\PasswordUpdateRequest;
use App\Http\Requests\Admin\ProfileUpdateRequest;
use App\Services\ProfileService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Features;

final class ProfileController extends Controller
{
    public function __construct(
        private readonly ProfileService $profileService,
    ) {}

    public function index()
    {
        return view('admin.profile.index', [
            'user' => auth()->user(),
        ]);
    }

    public function updateProfile(ProfileUpdateRequest $request)
    {
        $this->profileService->updateProfile(auth()->user(), $request->validated());

        return back()->with('success', __('messages.profile_information_updated'));
    }

    public function updateAppearance(AppearanceRequest $request)
    {
        $this->profileService->updateAppearance(auth()->user(), $request->validated());

        return back()->with('success', __('messages.appearance_updated'));
    }

    public function updatePassword(PasswordUpdateRequest $request)
    {
        $user = auth()->user();
        $validated = $request->validated();

        $success = $this->profileService->updatePassword(
            $user,
            $validated['current_password'],
            $validated['password']
        );

        if (! $success) {
            return back()->withErrors(['current_password' => __('validation.current_password')]);
        }

        return back()->with('success', __('messages.password_updated'));
    }

    public function enableTwoFactor()
    {
        if (! Features::canManageTwoFactorAuthentication()) {
            return back()->withErrors(['error' => 'Two-factor authentication is not enabled.']);
        }

        $user = auth()->user();

        $this->profileService->enableTwoFactor($user);

        return back()
            ->with('twoFactorQrCode', $user->twoFactorQrCodeSvg())
            ->with('twoFactorSecret', decrypt($user->two_factor_secret))
            ->with('showTwoFactorSetup', true);
    }

    public function confirmTwoFactor(Request $request)
    {
        $request->validate([
            'code' => ['required', 'string'],
        ]);

        $user = auth()->user();

        try {
            $this->profileService->confirmTwoFactor($user, $request->input('code'));
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors(), 'confirmTwoFactorAuthentication');
        }

        return back()
            ->with('success', __('messages.two_factor_enabled'))
            ->with('showRecoveryCodes', true)
            ->with('recoveryCodes', $user->recoveryCodes());
    }

    public function showRecoveryCodes()
    {
        $user = auth()->user();

        if (! $user->hasEnabledTwoFactorAuthentication()) {
            return back()->withErrors(['error' => 'Two-factor authentication is not enabled.']);
        }

        return back()
            ->with('showRecoveryCodes', true)
            ->with('recoveryCodes', $user->recoveryCodes());
    }

    public function regenerateRecoveryCodes()
    {
        $user = auth()->user();

        if (! $user->hasEnabledTwoFactorAuthentication()) {
            return back()->withErrors(['error' => 'Two-factor authentication is not enabled.']);
        }

        $this->profileService->regenerateRecoveryCodes($user);

        return back()
            ->with('success', __('messages.recovery_codes_regenerated'))
            ->with('showRecoveryCodes', true)
            ->with('recoveryCodes', $user->recoveryCodes());
    }

    public function disableTwoFactor()
    {
        if (! Features::canManageTwoFactorAuthentication()) {
            return back()->withErrors(['error' => 'Two-factor authentication is not enabled.']);
        }

        $this->profileService->disableTwoFactor(auth()->user());

        return back()->with('success', __('messages.two_factor_disabled'));
    }

    public function destroy(AccountDeleteRequest $request)
    {
        $success = $this->profileService->deleteAccount(
            auth()->user(),
            $request->validated()['password']
        );

        if (! $success) {
            return back()->withErrors(['password' => __('validation.current_password')]);
        }

        return redirect('/')->with('success', __('messages.account_deleted'));
    }
}
