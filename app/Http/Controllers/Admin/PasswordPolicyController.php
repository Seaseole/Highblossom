<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\PasswordPolicyRequest;
use App\Services\PasswordPolicy;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/**
 * Let administrators set the password strength rules used across the application.
 */
final class PasswordPolicyController
{
    public function __construct(
        private readonly PasswordPolicy $policy,
    ) {}

    /**
     * Display the password policy form.
     */
    public function index(): View
    {
        return view('admin.settings.password-policy', [
            'policy' => $this->policy->current(),
            'summary' => $this->policy->summary(),
            'trail' => $this->policy->auditTrail(),
        ]);
    }

    /**
     * Persist a new policy and record the change.
     */
    public function update(PasswordPolicyRequest $request): RedirectResponse
    {
        $this->policy->save($request->validated(), $request->user());

        return redirect()
            ->route('admin.password-policy.index')
            ->with('success', 'Password policy saved. Every password form now uses it.');
    }
}
