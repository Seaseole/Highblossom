<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Actions\StoreInspectionAction;
use App\Actions\UpdateInspectionAction;
use App\Http\Requests\Admin\StoreInspectionRequest;
use App\Http\Requests\Admin\UpdateInspectionRequest;
use App\Models\Inspection;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
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
        $inspection->load(['booking', 'staff']);

        // Add users with 'update inspections' permission (or simply all active users for now)
        $staffMembers = User::all(); // Alternatively \App\Models\User::permission('update inspections')->get()

        return view('admin.inspections.show', compact('inspection', 'staffMembers'));
    }

    /**
     * Store a new inspection from a booking.
     */
    public function store(StoreInspectionRequest $request): RedirectResponse
    {
        $inspection = app(StoreInspectionAction::class)->execute($request->validated());

        return redirect()
            ->route('admin.bookings.show', $inspection->booking_id)
            ->with('success', 'Inspection scheduled successfully.');
    }

    /**
     * Update the specified inspection.
     */
    public function update(UpdateInspectionRequest $request, Inspection $inspection): RedirectResponse
    {
        app(UpdateInspectionAction::class)->execute($inspection, $request->validated());

        return back()->with('success', 'Inspection updated.');
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
            ->with('success', 'Inspection deleted.');
    }
}
