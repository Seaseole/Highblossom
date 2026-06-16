<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Models\Inspection;
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
        $inspections = Inspection::query()->latest()->paginate(15);

        return view('admin.inspections.index', compact('inspections'));
    }

    /**
     * Display the specified inspection with related bookings and staff.
     */
    public function show(Inspection $inspection): View
    {
        $inspection->load(['booking', 'staff']);

        return view('admin.inspections.show', compact('inspection'));
    }
}
