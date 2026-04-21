<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Domains\Bookings\Models\Inspection;
use Illuminate\View\View;

final class InspectionController
{
    public function index(): View
    {
        $inspections = Inspection::query()->latest()->paginate(15);

        return view('admin.inspections.index', compact('inspections'));
    }

    public function show(Inspection $inspection): View
    {
        $inspection->load(['booking', 'staff']);

        return view('admin.inspections.show', compact('inspection'));
    }
}
