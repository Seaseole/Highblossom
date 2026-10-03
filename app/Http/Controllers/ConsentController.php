<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\ConsentRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Capture terms and privacy consent from users who registered before
 * consent was recorded (or whose accounts were created another way).
 */
final class ConsentController
{
    /**
     * Show the consent capture form.
     */
    public function show(Request $request): View
    {
        return view('auth.consent');
    }

    /**
     * Record acceptance of both policies and return the user to their
     * originally intended destination.
     */
    public function store(ConsentRequest $request): RedirectResponse
    {
        $request->user()->update([
            'terms_accepted_at' => now(),
            'privacy_accepted_at' => now(),
        ]);

        return redirect()->intended(route('dashboard'));
    }
}
