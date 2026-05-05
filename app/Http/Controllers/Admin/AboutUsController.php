<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\AboutUsRequest;
use App\Services\AboutUsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

final class AboutUsController
{
    public function __construct(
        private readonly AboutUsService $aboutUsService,
    ) {}

    public function edit(): View
    {
        $content = $this->aboutUsService->getOrCreateContent();

        return view('admin.about-us.edit', compact('content'));
    }

    public function update(AboutUsRequest $request): RedirectResponse
    {
        $this->aboutUsService->update($request->validated(), $request);

        return redirect()->back()->with('success', 'About Us content updated successfully.');
    }
}
