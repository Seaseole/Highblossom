<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Actions\StoreInspectionAction;
use App\Actions\UpdateInspectionAction;
use App\Http\Requests\Admin\StoreInspectionNoteRequest;
use App\Http\Requests\Admin\StoreInspectionRequest;
use App\Http\Requests\Admin\UpdateInspectionRequest;
use App\Models\Inspection;
use App\Models\InspectionNote;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Manage inspections in the admin panel.
 */
final class InspectionController
{
    /**
     * Display a paginated list of inspections.
     */
    public function index(): View
    {
        $inspections = Inspection::with(['booking', 'staff'])
            ->latest('scheduled_at')
            ->paginate(15);

        return view('admin.inspections.index', compact('inspections'));
    }

    /**
     * Display the specified inspection with related bookings and staff.
     */
    public function show(Inspection $inspection): View
    {
        $inspection->load(['booking', 'staff', 'notes.author']);

        // Add users with 'update inspections' permission (or simply all active users for now)
        $staffMembers = User::all(); // Alternatively \App\Models\User::permission('update inspections')->get()

        return view('admin.inspections.show', compact('inspection', 'staffMembers'));
    }

    /**
     * Store a new inspection from a booking.
     */
    public function store(StoreInspectionRequest $request): RedirectResponse
    {
        $inspection = app(StoreInspectionAction::class)->execute(
            $request->validated(),
            $request->user()?->id
        );

        return redirect()
            ->route('admin.bookings.show', $inspection->booking_id)
            ->with('success', 'Appointment scheduled successfully.');
    }

    /**
     * Update the specified inspection.
     */
    public function update(UpdateInspectionRequest $request, Inspection $inspection): RedirectResponse
    {
        app(UpdateInspectionAction::class)->execute(
            $inspection,
            $request->validated(),
            $request->user()?->id
        );

        return back()->with('success', 'Appointment updated.');
    }

    /**
     * Delete the specified inspection.
     */
    public function destroy(Inspection $inspection): RedirectResponse
    {
        $bookingId = $inspection->booking_id;
        $inspection->delete();

        return redirect()
            ->route('admin.bookings.show', $bookingId)
            ->with('success', 'Appointment deleted.');
    }

    /**
     * Store an internal note on the specified inspection.
     */
    public function storeNote(StoreInspectionNoteRequest $request, Inspection $inspection): RedirectResponse
    {
        $inspection->notes()->create([
            ...$request->validated(),
            'user_id' => $request->user()?->id,
        ]);

        return back()->with('success', 'Note added.');
    }

    /**
     * Mark an action-item note as done, or reopen it.
     */
    public function toggleNoteDone(Request $request, Inspection $inspection, InspectionNote $note): RedirectResponse
    {
        $this->ensureNoteBelongsTo($inspection, $note);

        $note->update(['done_at' => $note->done_at === null ? now() : null]);

        return back()->with('success', $note->isOpen() ? 'Action reopened.' : 'Action marked done.');
    }

    /**
     * Delete an internal note.
     */
    public function destroyNote(Inspection $inspection, InspectionNote $note): RedirectResponse
    {
        $this->ensureNoteBelongsTo($inspection, $note);

        $note->delete();

        return back()->with('success', 'Note removed.');
    }

    /**
     * Reject a note that belongs to another inspection.
     *
     * Route binding resolves the note by its own id, so the parent segment in the
     * URL proves nothing on its own.
     */
    private function ensureNoteBelongsTo(Inspection $inspection, InspectionNote $note): void
    {
        abort_unless($note->inspection_id === $inspection->id, 404);
    }
}
