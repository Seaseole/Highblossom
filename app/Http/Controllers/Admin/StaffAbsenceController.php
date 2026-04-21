<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Domains\Bookings\Models\StaffAbsence;
use Illuminate\View\View;

final class StaffAbsenceController
{
    public function index(): View
    {
        $absences = StaffAbsence::query()->latest()->paginate(15);

        return view('admin.absences.index', compact('absences'));
    }

    public function show(StaffAbsence $absence): View
    {
        $absence->load('staff');

        return view('admin.absences.show', compact('absence'));
    }
}
