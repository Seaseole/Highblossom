<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Models\StaffAbsence;
use Illuminate\View\View;

/**
 * Manage staff absences in the admin panel.
 */
final class StaffAbsenceController
{
    /**
     * Display a paginated list of staff absences.
     */
    public function index(): View
    {
        $absences = StaffAbsence::query()->latest()->paginate(15);

        return view('admin.absences.index', compact('absences'));
    }

    /**
     * Display the specified absence with staff details.
     */
    public function show(StaffAbsence $absence): View
    {
        $absence->load('staff');

        return view('admin.absences.show', compact('absence'));
    }
}
