<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\StaffResource;
use App\Models\Staff;
use App\Support\SortOrderRules;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Storage;

/**
 * Manage staff team members for the app.
 *
 * Mirrors the web admin's validation and ordering. The photo is optional here
 * (the web form requires it) so the mobile client can create/edit the text
 * profile now and attach a photo later. Gated by `can:manage settings`.
 */
final class StaffController extends Controller
{
    /**
     * List staff ordered by position.
     */
    public function index(): AnonymousResourceCollection
    {
        return StaffResource::collection(Staff::orderBy('order')->orderBy('id')->get());
    }

    /**
     * Show one staff member.
     */
    public function show(Staff $staff): StaffResource
    {
        return new StaffResource($staff);
    }

    /**
     * Create a staff member.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'role' => ['required', 'string', 'max:255'],
            'bio' => ['nullable', 'string'],
            'photo' => ['nullable', 'image', 'max:2048'],
            'is_active' => ['boolean'],
            'order' => SortOrderRules::rules(Staff::class, $request->all()),
        ]);

        $staff = Staff::create([
            'name' => $validated['name'],
            'role' => $validated['role'],
            'bio' => $validated['bio'] ?? null,
            'photo_path' => $request->hasFile('photo')
                ? $request->file('photo')->store('staff', 'public')
                : null,
            'is_active' => $request->boolean('is_active', true),
            'order' => $validated['order'] ?? null,
        ]);

        return response()->json(['data' => new StaffResource($staff)], 201);
    }

    /**
     * Update a staff member.
     */
    public function update(Request $request, Staff $staff): StaffResource
    {
        SortOrderRules::normalize($request, Staff::class, $staff);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'role' => ['required', 'string', 'max:255'],
            'bio' => ['nullable', 'string'],
            'photo' => ['nullable', 'image', 'max:2048'],
            'is_active' => ['boolean'],
            'order' => SortOrderRules::rules(Staff::class, $request->all(), $staff),
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
            'bio' => $validated['bio'] ?? null,
            'is_active' => $request->boolean('is_active', $staff->is_active),
            'order' => $validated['order'],
        ]);

        return new StaffResource($staff->fresh());
    }

    /**
     * Delete a staff member and their photo.
     */
    public function destroy(Staff $staff): JsonResponse
    {
        if ($staff->photo_path) {
            Storage::disk('public')->delete($staff->photo_path);
        }
        $staff->delete();

        return response()->json(['message' => 'Staff member removed.']);
    }
}
