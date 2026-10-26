<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Actions\StoreInspectionAction;
use App\Actions\UpdateInspectionAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreInspectionNoteRequest;
use App\Http\Requests\Admin\StoreInspectionRequest;
use App\Http\Requests\Admin\UpdateInspectionRequest;
use App\Http\Resources\Api\InspectionNoteResource;
use App\Http\Resources\Api\InspectionResource;
use App\Models\Inspection;
use App\Models\InspectionNote;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * Read and manage inspections (appointments) and their internal notes for the staff app.
 *
 * Creation and updates go through the same actions the web admin uses, so scheduling
 * still confirms the booking and starting/completing still records timeline milestones.
 * Authorization is enforced by the `can:view inspections` / `can:update inspections`
 * route middleware.
 */
final class InspectionController extends Controller
{
    /**
     * List inspections, newest appointment first.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $inspections = Inspection::query()
            ->with(['booking', 'staff'])
            ->latest('scheduled_at')
            ->paginate(min(100, max(1, $request->integer('per_page', 15))));

        return InspectionResource::collection($inspections);
    }

    /**
     * Show one inspection with its booking, assigned staff and internal notes.
     */
    public function show(Inspection $inspection): InspectionResource
    {
        $inspection->load(['booking', 'staff', 'notes.author']);

        return new InspectionResource($inspection);
    }

    /**
     * Schedule a new inspection against a booking.
     */
    public function store(StoreInspectionRequest $request): JsonResponse
    {
        $inspection = app(StoreInspectionAction::class)
            ->execute($request->validated(), $request->user()?->id);

        $inspection->load(['booking', 'staff']);

        return response()->json([
            'data' => new InspectionResource($inspection),
            'message' => 'Appointment scheduled.',
        ], 201);
    }

    /**
     * Update an inspection, recording the milestones its changes imply.
     */
    public function update(UpdateInspectionRequest $request, Inspection $inspection): InspectionResource
    {
        $inspection = app(UpdateInspectionAction::class)
            ->execute($inspection, $request->validated(), $request->user()?->id);

        $inspection->load(['booking', 'staff']);

        return new InspectionResource($inspection);
    }

    /**
     * Delete an inspection.
     */
    public function destroy(Inspection $inspection): JsonResponse
    {
        $inspection->delete();

        return response()->json(['message' => 'Appointment deleted.']);
    }

    /**
     * Add an internal note to an inspection.
     */
    public function storeNote(StoreInspectionNoteRequest $request, Inspection $inspection): JsonResponse
    {
        $note = $inspection->notes()->create([
            ...$request->validated(),
            'user_id' => $request->user()?->id,
        ]);

        $note->load('author');

        return response()->json([
            'data' => new InspectionNoteResource($note),
            'message' => 'Note added.',
        ], 201);
    }

    /**
     * Mark an action-item note done, or reopen it.
     */
    public function toggleNoteDone(Request $request, Inspection $inspection, InspectionNote $note): InspectionNoteResource
    {
        $this->ensureNoteBelongsTo($inspection, $note);

        $note->update(['done_at' => $note->done_at === null ? now() : null]);

        return new InspectionNoteResource($note->fresh());
    }

    /**
     * Delete an internal note.
     */
    public function destroyNote(Inspection $inspection, InspectionNote $note): JsonResponse
    {
        $this->ensureNoteBelongsTo($inspection, $note);

        $note->delete();

        return response()->json(['message' => 'Note removed.']);
    }

    /**
     * Reject a note that belongs to another inspection.
     *
     * Route binding resolves the note by its own id, so the parent segment proves nothing.
     */
    private function ensureNoteBelongsTo(Inspection $inspection, InspectionNote $note): void
    {
        abort_unless($note->inspection_id === $inspection->id, 404);
    }
}
