<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TestimonialRequest;
use App\Http\Resources\Api\TestimonialResource;
use App\Models\Testimonial;
use App\Services\TestimonialService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * Manage testimonials for the staff app.
 *
 * Create/update reuse the admin's TestimonialRequest (so sort-order normalization
 * and boolean coercion behave identically) and TestimonialService. Authorization
 * mirrors the web admin: every action is gated by `can:manage testimonials`.
 */
final class TestimonialController extends Controller
{
    public function __construct(private readonly TestimonialService $testimonialService) {}

    /**
     * List testimonials in sort order, optionally searched and filtered by published state.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $testimonials = Testimonial::query()
            ->when($request->query('search'), fn ($q, $search) => $q->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('role', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%");
            }))
            ->when(
                $request->query('published'),
                fn ($q, $published) => $q->where('is_published', filter_var($published, FILTER_VALIDATE_BOOLEAN))
            )
            ->ordered()
            ->paginate(min(100, max(1, $request->integer('per_page', 15))));

        return TestimonialResource::collection($testimonials);
    }

    /**
     * Show one testimonial.
     */
    public function show(Testimonial $testimonial): TestimonialResource
    {
        return new TestimonialResource($testimonial);
    }

    /**
     * Create a testimonial.
     */
    public function store(TestimonialRequest $request): JsonResponse
    {
        $testimonial = $this->testimonialService->create($request->validated());

        return response()->json([
            'data' => new TestimonialResource($testimonial),
            'message' => __('messages.testimonial_created'),
        ], 201);
    }

    /**
     * Update a testimonial.
     */
    public function update(TestimonialRequest $request, Testimonial $testimonial): JsonResponse
    {
        $testimonial = $this->testimonialService->update($testimonial, $request->validated());

        return response()->json([
            'data' => new TestimonialResource($testimonial),
            'message' => __('messages.testimonial_updated'),
        ]);
    }

    /**
     * Delete a testimonial.
     */
    public function destroy(Testimonial $testimonial): JsonResponse
    {
        $this->testimonialService->delete($testimonial);

        return response()->json(['message' => __('messages.testimonial_deleted')]);
    }
}
