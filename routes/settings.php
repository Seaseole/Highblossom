<?php

use App\Http\Controllers\Auth\LogoutController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    // TODO: Migrate to controller + Blade view
    // Route::livewire('settings/profile', 'pages::settings.profile')->name('profile.edit');

    Route::post('/logout', LogoutController::class)->name('logout');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // TODO: Migrate to controller + Blade view
    // Route::livewire('settings/appearance', 'pages::settings.appearance')->name('appearance.edit');

    // Route::livewire('settings/security', 'pages::settings.security')
    //     ->middleware(
    //         when(
    //             Features::canManageTwoFactorAuthentication()
    //                 && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
    //             ['password.confirm'],
    //             [],
    //         ),
    //     )
    //     ->name('security.edit');
});
