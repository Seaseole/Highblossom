<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Image\ImageException;
use Illuminate\Support\Facades\Image;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

/**
 * Manage CRUD operations for partner logos and links.
 */
final class PartnerController extends Controller
{
    /**
     * Display a list of all partners ordered by position.
     */
    public function index(): View
    {
        $partners = Partner::orderBy('order')->get();

        return view('admin.partners.index', compact('partners'));
    }

    /**
     * Show the form for creating a new partner.
     */
    public function create(): View
    {
        return view('admin.partners.create');
    }

    /**
     * Store a newly created partner in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'required|image|max:2048',
            'website_url' => 'nullable|url',
        ]);

        try {
            $path = Image::fromUpload($request->file('logo'))
                ->contain(width: 800, height: 800)
                ->toWebp()
                ->quality(82)
                ->store('partners', 'public');
        } catch (ImageException $e) {
            Log::error('Failed to process partner logo: '.$e->getMessage());
            $path = $request->file('logo')->store('partners', 'public');
        }

        Partner::create([
            'name' => $validated['name'],
            'logo_path' => $path,
            'website_url' => $validated['website_url'] ?? null,
        ]);

        return redirect()->route('admin.partners.index')->with('success', 'Partner added successfully.');
    }

    /**
     * Show the form for editing the specified partner.
     */
    public function edit(Partner $partner): View
    {
        return view('admin.partners.edit', compact('partner'));
    }

    /**
     * Update the specified partner in storage.
     */
    public function update(Request $request, Partner $partner): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|max:2048',
            'website_url' => 'nullable|url',
        ]);

        if ($request->hasFile('logo')) {
            try {
                Storage::disk('public')->delete($partner->logo_path);
                $partner->logo_path = Image::fromUpload($request->file('logo'))
                    ->contain(width: 800, height: 800)
                    ->toWebp()
                    ->quality(82)
                    ->store('partners', 'public');
            } catch (ImageException $e) {
                Log::error('Failed to process partner logo: '.$e->getMessage());
                Storage::disk('public')->delete($partner->logo_path);
                $partner->logo_path = $request->file('logo')->store('partners', 'public');
            }
        }

        $partner->update([
            'name' => $validated['name'],
            'website_url' => $validated['website_url'] ?? null,
        ]);

        return redirect()->route('admin.partners.index')->with('success', 'Partner updated successfully.');
    }

    /**
     * Remove the specified partner from storage.
     */
    public function destroy(Partner $partner): RedirectResponse
    {
        Storage::disk('public')->delete($partner->logo_path);
        $partner->delete();

        return redirect()->route('admin.partners.index')->with('success', 'Partner removed.');
    }
}
