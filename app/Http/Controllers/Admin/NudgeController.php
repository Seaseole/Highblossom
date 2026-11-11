<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Models\Nudge;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;

/**
 * Dismiss and snooze actions for the rule-based nudges.
 *
 * The permission check is per row: a nudge carries the permission its action
 * needs, and a viewer who cannot perform that action can neither see the nudge
 * nor dismiss it for everyone else.
 */
final class NudgeController
{
    /**
     * Hide the nudge for the current occurrence of its signal.
     */
    public function dismiss(Nudge $nudge): RedirectResponse
    {
        $this->authorizeNudge($nudge);

        $nudge->update(['status' => Nudge::STATUS_DISMISSED, 'snoozed_until' => null]);

        return back()->with('success', __('admin-nudges.dismissed'));
    }

    /**
     * Hide the nudge until the snooze deadline; the scanner reopens it after.
     */
    public function snooze(Nudge $nudge): RedirectResponse
    {
        $this->authorizeNudge($nudge);

        $nudge->update([
            'status' => Nudge::STATUS_SNOOZED,
            'snoozed_until' => now()->addDays((int) config('ai.nudges.snooze_days', 3)),
        ]);

        return back()->with('success', __('admin-nudges.snoozed'));
    }

    /**
     * Both actions require the feature on and the nudge's own permission.
     */
    private function authorizeNudge(Nudge $nudge): void
    {
        abort_unless(config('ai.enabled'), 404);
        abort_unless($nudge->permission === null || Gate::allows($nudge->permission), 403);
    }
}
