<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AccountDeleteRequest;
use App\Http\Requests\Admin\AppearanceRequest;
use App\Http\Requests\Admin\PasswordUpdateRequest;
use App\Http\Requests\Admin\ProfileUpdateRequest;
use App\Services\ProfileService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Laravel\Fortify\Features;

/**
 * Manage the authenticated user's profile, password, and two-factor authentication.
 */
final class ProfileController extends Controller
{
    public function __construct(
        private readonly ProfileService $profileService,
    ) {}

    /**
     * Display the profile page with user information and optional QR code.
     *
     * @return View
     */
    public function index()
    {
        $user = auth()->user();
        $qrCodeSvg = null;

        if ($user->two_factor_secret && ! $user->two_factor_confirmed_at) {
            $qrCodeSvg = $this->profileService->getTwoFactorQrCodeSvg($user);
        }

        return view('admin.profile.index', [
            'user' => $user,
            'qrCodeSvg' => $qrCodeSvg,
        ]);
    }

    /**
     * Update the user's profile information.
     *
     * @return RedirectResponse
     */
    public function updateProfile(ProfileUpdateRequest $request)
    {
        $this->profileService->updateProfile(auth()->user(), $request->validated());

        return back()->with('success', __('messages.profile_information_updated'));
    }

    /**
     * Update the user's appearance preferences.
     *
     * @return RedirectResponse
     */
    public function updateAppearance(AppearanceRequest $request)
    {
        $this->profileService->updateAppearance(auth()->user(), $request->validated());

        return back()->with('success', __('messages.appearance_updated'));
    }

    /**
     * Update the user's password.
     *
     * @return RedirectResponse
     */
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

    /**
     * Enable two-factor authentication for the user.
     *
     * @return RedirectResponse
     */
    public function enableTwoFactor()
    {
        if (! Features::canManageTwoFactorAuthentication()) {
            return back()->withErrors(['error' => 'Two-factor authentication is not enabled.']);
        }

        $this->profileService->enableTwoFactor(auth()->user());

        return back()->with('success', 'Two-factor authentication setup started. Please scan the QR code to confirm.');
    }

    /**
     * Confirm two-factor authentication with a verification code.
     *
     * @return RedirectResponse
     */
    public function confirmTwoFactor(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        $success = $this->profileService->confirmTwoFactor(auth()->user(), $request->code);

        if (! $success) {
            return back()->withErrors(['code' => 'The provided two-factor authentication code was invalid.']);
        }

        session()->flash('recovery_codes', auth()->user()->recoveryCodes());

        return back()->with('success', __('messages.two_factor_enabled'));
    }

    /**
     * Disable two-factor authentication for the user.
     *
     * @return RedirectResponse
     */
    public function disableTwoFactor()
    {
        if (! Features::canManageTwoFactorAuthentication()) {
            return back()->withErrors(['error' => 'Two-factor authentication is not enabled.']);
        }

        $this->profileService->disableTwoFactor(auth()->user());

        return back()->with('success', __('messages.two_factor_disabled'));
    }

    /**
     * Display the user's recovery codes.
     *
     * @return JsonResponse
     */
    public function showRecoveryCodes()
    {
        $user = auth()->user();

        if (! $user->two_factor_secret || ! $user->two_factor_confirmed_at) {
            return response()->json(['message' => 'Two-factor authentication is not confirmed.'], 403);
        }

        $codes = $user->recoveryCodes();

        return response()->json([
            'recovery_codes' => is_array($codes) ? array_values($codes) : [],
        ]);
    }

    /**
     * Regenerate the user's recovery codes.
     *
     * @return JsonResponse|RedirectResponse
     */
    public function regenerateRecoveryCodes(Request $request)
    {
        $this->profileService->regenerateRecoveryCodes(auth()->user());

        if ($request->wantsJson()) {
            $codes = auth()->user()->recoveryCodes();

            return response()->json([
                'recovery_codes' => is_array($codes) ? array_values($codes) : [],
            ]);
        }

        return back()->with('success', 'Recovery codes regenerated.')
            ->with('recovery_codes', auth()->user()->recoveryCodes());
    }

    /**
     * Delete the user's account.
     *
     * @return RedirectResponse
     */
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
