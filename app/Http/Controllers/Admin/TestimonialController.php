<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\TestimonialRequest;
use App\Models\Testimonial;
use App\Services\TestimonialService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/**
 * Manage CRUD operations for testimonials.
 */
final class TestimonialController
{
    public function __construct(
        private readonly TestimonialService $testimonialService,
    ) {}

    /**
     * Display a paginated list of testimonials.
     */
    public function index(): View
    {
        $testimonials = Testimonial::query()->latest()->paginate(15);

        return view('admin.testimonials.index', compact('testimonials'));
    }

    /**
     * Show the form for creating a new testimonial.
     */
    public function create(): View
    {
        return view('admin.testimonials.create');
    }

    /**
     * Store a newly created testimonial in storage.
     *
     * @return RedirectResponse
     */
    public function store(TestimonialRequest $request)
    {
        $this->testimonialService->create($request->validated());

        return redirect()
            ->route('admin.testimonials.index')
            ->with('success', __('messages.testimonial_created'));
    }

    /**
     * Show the form for editing the specified testimonial.
     */
    public function edit(Testimonial $testimonial): View
    {
        return view('admin.testimonials.edit', compact('testimonial'));
    }

    /**
     * Update the specified testimonial in storage.
     *
     * @return RedirectResponse
     */
    public function update(TestimonialRequest $request, Testimonial $testimonial)
    {
        $this->testimonialService->update($testimonial, $request->validated());

        return redirect()
            ->route('admin.testimonials.index')
            ->with('success', __('messages.testimonial_updated'));
    }

    /**
     * Remove the specified testimonial from storage.
     *
     * @return RedirectResponse
     */
    public function destroy(Testimonial $testimonial)
    {
        $this->testimonialService->delete($testimonial);

        return redirect()
            ->route('admin.testimonials.index')
            ->with('success', __('messages.testimonial_deleted'));
    }
}
