<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\QuoteStatusRequest;
use App\Http\Resources\Api\QuoteResource;
use App\Models\Quote;
use App\Services\QuoteService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * Read and triage quote requests for the staff app.
 *
 * Status changes and deletes go through the same QuoteService the web admin uses.
 * Authorization mirrors the admin routes: `can:view bookings` to read,
 * `can:update bookings` to change status or delete.
 */
final class QuoteController extends Controller
{
    public function __construct(private readonly QuoteService $quoteService) {}

    /**
     * List quotes, optionally filtered by status and a client/vehicle search.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $quotes = Quote::query()
            ->with(['glassType', 'serviceType', 'glassSubCategory'])
            ->when($request->query('status'), fn ($q, $status) => $q->where('status', $status))
            ->when($request->query('search'), fn ($q, $search) => $q->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('reg_number', 'like', "%{$search}%");
            }))
            ->latest()
            ->paginate(min(100, max(1, $request->integer('per_page', 20))));

        return QuoteResource::collection($quotes);
    }

    /**
     * Show one quote with its glass and service names.
     */
    public function show(Quote $quote): QuoteResource
    {
        $quote->load(['glassType', 'serviceType', 'glassSubCategory']);

        return new QuoteResource($quote);
    }

    /**
     * Move a quote through pending → contacted → completed / cancelled.
     */
    public function updateStatus(QuoteStatusRequest $request, Quote $quote): JsonResponse
    {
        $quote = $this->quoteService->updateStatus($quote, $request->input('status'));
        $quote->load(['glassType', 'serviceType', 'glassSubCategory']);

        return response()->json([
            'data' => new QuoteResource($quote),
            'message' => __('messages.quote_status_updated'),
        ]);
    }

    /**
     * Delete a quote.
     */
    public function destroy(Quote $quote): JsonResponse
    {
        $this->quoteService->delete($quote);

        return response()->json(['message' => __('messages.quote_deleted')]);
    }
}
