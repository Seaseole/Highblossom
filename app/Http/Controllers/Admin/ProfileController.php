<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Features;

class ProfileController extends Controller
{
    public function index()
    {
        return view('admin.profile.index', [
            'user' => auth()->user(),
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
        ]);

        $user->update($validated);

        return back()->with('success', __('messages.profile_information_updated'));
    }

    public function updateAppearance(Request $request)
    {
        $validated = $request->validate([
            'theme_preference' => ['required', 'in:light,dark,auto'],
        ]);

        auth()->user()->update($validated);

        return back()->with('success', __('messages.appearance_updated'));
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = auth()->user();

        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => __('validation.current_password')]);
        }

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', __('messages.password_updated'));
    }

    public function enableTwoFactor(Request $request)
    {
        if (!Features::canManageTwoFactorAuthentication()) {
            return back()->withErrors(['error' => 'Two-factor authentication is not enabled.']);
        }

        $user = auth()->user();

        // Enable 2FA - this is a simplified version
        // In a real implementation, you would generate QR code, show recovery codes, etc.
        $user->forceFill([
            'two_factor_secret' => encrypt('test-secret'),
            'two_factor_confirmed_at' => now(),
        ])->save();

        return back()->with('success', __('messages.two_factor_enabled'));
    }

    public function disableTwoFactor(Request $request)
    {
        if (!Features::canManageTwoFactorAuthentication()) {
            return back()->withErrors(['error' => 'Two-factor authentication is not enabled.']);
        }

        $user = auth()->user();

        $user->forceFill([
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at' => null,
        ])->save();

        return back()->with('success', __('messages.two_factor_disabled'));
    }

    public function destroy(Request $request)
    {
        $validated = $request->validate([
            'password' => ['required', 'string'],
        ]);

        $user = auth()->user();

        if (!Hash::check($validated['password'], $user->password)) {
            return back()->withErrors(['password' => __('validation.current_password')]);
        }

        auth()->logout();

        $user->delete();

        return redirect('/')->with('success', __('messages.account_deleted'));
    }
}
