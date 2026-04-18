<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class StaffAbsenceController extends Controller
{
    public function index()
    {
        return view('admin.absences.index');
    }
}
