<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class InspectionController extends Controller
{
    public function index()
    {
        return view('admin.inspections.index');
    }
}
