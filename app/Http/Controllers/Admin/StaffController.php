<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

final class StaffController extends Controller
{
    public function index(): View
    {
        $staff = Staff::orderBy('order')->get();

        return view('admin.staff.index', compact('staff'));
    }

    public function create(): View
    {
        return view('admin.staff.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'photo' => 'required|image|max:2048',
        ]);

        $path = $request->file('photo')->store('staff', 'public');

        Staff::create([
            'name' => $validated['name'],
            'role' => $validated['role'],
            'bio' => $validated['bio'],
            'photo_path' => $path,
        ]);

        return redirect()->route('admin.staff.index')->with('success', 'Staff member added.');
    }

    public function edit(Staff $staff): View
    {
        return view('admin.staff.edit', compact('staff'));
    }

    public function update(Request $request, Staff $staff): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'photo' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            if ($staff->photo_path) {
                Storage::disk('public')->delete($staff->photo_path);
            }
            $staff->photo_path = $request->file('photo')->store('staff', 'public');
        }

        $staff->update([
            'name' => $validated['name'],
            'role' => $validated['role'],
            'bio' => $validated['bio'],
        ]);

        return redirect()->route('admin.staff.index')->with('success', 'Staff member updated.');
    }

    public function destroy(Staff $staff): RedirectResponse
    {
        if ($staff->photo_path) {
            Storage::disk('public')->delete($staff->photo_path);
        }
        $staff->delete();

        return redirect()->route('admin.staff.index')->with('success', 'Staff member removed.');
    }
}
