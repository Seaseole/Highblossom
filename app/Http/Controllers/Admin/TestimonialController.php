<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\TestimonialRequest;
use App\Models\Testimonial;
use App\Services\TestimonialService;
use Illuminate\View\View;

final class TestimonialController
{
    public function __construct(
        private readonly TestimonialService $testimonialService,
    ) {}

    public function index(): View
    {
        $testimonials = Testimonial::query()->latest()->paginate(15);

        return view('admin.testimonials.index', compact('testimonials'));
    }

    public function create(): View
    {
        return view('admin.testimonials.create');
    }

    public function store(TestimonialRequest $request)
    {
        $this->testimonialService->create($request->validated());

        return redirect()
            ->route('admin.testimonials.index')
            ->with('success', __('messages.testimonial_created'));
    }

    public function edit(Testimonial $testimonial): View
    {
        return view('admin.testimonials.edit', compact('testimonial'));
    }

    public function update(TestimonialRequest $request, Testimonial $testimonial)
    {
        $this->testimonialService->update($testimonial, $request->validated());

        return redirect()
            ->route('admin.testimonials.index')
            ->with('success', __('messages.testimonial_updated'));
    }

    public function destroy(Testimonial $testimonial)
    {
        $this->testimonialService->delete($testimonial);

        return redirect()
            ->route('admin.testimonials.index')
            ->with('success', __('messages.testimonial_deleted'));
    }
}
