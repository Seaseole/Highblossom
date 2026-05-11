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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Features;

final class ProfileController extends Controller
{
    public function __construct(
        private readonly ProfileService $profileService,
    ) {}

    public function index()
    {
        return view('admin.profile.index', [
            'user' => Auth::user(),
        ]);
    }

    public function updateProfile(ProfileUpdateRequest $request)
    {
        $this->profileService->updateProfile(Auth::user(), $request->validated());

        return back()->with('success', __('messages.profile_information_updated'));
    }

    public function updateAppearance(AppearanceRequest $request)
    {
        $this->profileService->updateAppearance(Auth::user(), $request->validated());

        return back()->with('success', __('messages.appearance_updated'));
    }

    public function updatePassword(PasswordUpdateRequest $request)
    {
        $user = Auth::user();
        $validated = $request->validated();

        $success = $this->profileService->updatePassword(
            $user,
            $validated['current_password'],
            $validated['password']
        );

        if (!$success) {
            return back()->withErrors(['current_password' => __('validation.current_password')]);
        }

        return back()->with('success', __('messages.password_updated'));
    }

    public function enableTwoFactor(Request $request): JsonResponse
    {
        if (!Features::canManageTwoFactorAuthentication()) {
            return response()->json(['error' => 'Two-factor authentication is not enabled.'], 403);
        }

        try {
            $tfaData = $this->profileService->enableTwoFactor(Auth::user());
            
            return response()->json([
                'success' => true,
                'secret' => $tfaData['secret'],
                'qr_code_url' => $tfaData['qr_code_url'],
                'recovery_codes' => $tfaData['recovery_codes'],
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function confirmTwoFactor(Request $request): JsonResponse
    {
        if (!Features::canManageTwoFactorAuthentication()) {
            return response()->json(['error' => 'Two-factor authentication is not enabled.'], 403);
        }

        $request->validate([
            'code' => 'required|string|digits:6',
        ]);

        $success = $this->profileService->confirmTwoFactor(
            Auth::user(),
            $request->input('code')
        );

        if ($success) {
            return response()->json([
                'success' => true,
                'message' => __('messages.two_factor_confirmed'),
            ]);
        }

        return response()->json([
            'error' => 'The provided two factor authentication code was invalid.',
        ], 422);
    }

    public function disableTwoFactor(Request $request): JsonResponse
    {
        if (!Features::canManageTwoFactorAuthentication()) {
            return response()->json(['error' => 'Two-factor authentication is not enabled.'], 403);
        }

        $request->validate([
            'password' => 'required|string',
        ]);

        $success = $this->profileService->disableTwoFactor(Auth::user());

        if ($success) {
            return response()->json([
                'success' => true,
                'message' => __('messages.two_factor_disabled'),
            ]);
        }

        return response()->json([
            'error' => 'Failed to disable two-factor authentication.',
        ], 500);
    }

    public function getRecoveryCodes(Request $request): JsonResponse
    {
        if (!Features::canManageTwoFactorAuthentication()) {
            return response()->json(['error' => 'Two-factor authentication is not enabled.'], 403);
        }

        $recoveryCodes = $this->profileService->getRecoveryCodes(Auth::user());

        return response()->json([
            'recovery_codes' => $recoveryCodes,
        ]);
    }

    public function regenerateRecoveryCodes(Request $request): JsonResponse
    {
        if (!Features::canManageTwoFactorAuthentication()) {
            return response()->json(['error' => 'Two-factor authentication is not enabled.'], 403);
        }

        $request->validate([
            'password' => 'required|string',
        ]);

        try {
            $recoveryCodes = $this->profileService->generateNewRecoveryCodes(Auth::user());
            
            return response()->json([
                'success' => true,
                'recovery_codes' => $recoveryCodes,
                'message' => 'Recovery codes regenerated successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy(AccountDeleteRequest $request)
    {
        $success = $this->profileService->deleteAccount(
            Auth::user(),
            $request->validated()['password']
        );

        if (!$success) {
            return back()->withErrors(['password' => __('validation.current_password')]);
        }

        return redirect('/')->with('success', __('messages.account_deleted'));
    }
}
