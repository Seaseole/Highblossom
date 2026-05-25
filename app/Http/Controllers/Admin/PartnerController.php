<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

final class PartnerController extends Controller
{
    public function index(): View
    {
        $partners = Partner::orderBy('order')->get();
        return view('admin.partners.index', compact('partners'));
    }

    public function create(): View
    {
        return view('admin.partners.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'required|image|max:2048',
            'website_url' => 'nullable|url',
        ]);

        $path = $request->file('logo')->store('partners', 'public');
        
        Partner::create([
            'name' => $validated['name'],
            'logo_path' => $path,
            'website_url' => $validated['website_url'] ?? null,
        ]);

        return redirect()->route('admin.partners.index')->with('success', 'Partner added successfully.');
    }

    public function edit(Partner $partner): View
    {
        return view('admin.partners.edit', compact('partner'));
    }

    public function update(Request $request, Partner $partner): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|max:2048',
            'website_url' => 'nullable|url',
        ]);

        if ($request->hasFile('logo')) {
            Storage::disk('public')->delete($partner->logo_path);
            $partner->logo_path = $request->file('logo')->store('partners', 'public');
        }

        $partner->update([
            'name' => $validated['name'],
            'website_url' => $validated['website_url'] ?? null,
        ]);

        return redirect()->route('admin.partners.index')->with('success', 'Partner updated successfully.');
    }

    public function destroy(Partner $partner): RedirectResponse
    {
        Storage::disk('public')->delete($partner->logo_path);
        $partner->delete();
        return redirect()->route('admin.partners.index')->with('success', 'Partner removed.');
    }
}
