<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\ServiceRequest;
use App\Models\Service;
use App\Services\ServiceService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/**
 * Manage CRUD operations for services.
 */
final class ServiceController
{
    public function __construct(
        private readonly ServiceService $serviceService,
    ) {}

    /**
     * Display a paginated list of services.
     */
    public function index(): View
    {
        $services = Service::query()->latest()->paginate(15);

        return view('admin.services.index', compact('services'));
    }

    /**
     * Show the form for creating a new service.
     */
    public function create(): View
    {
        return view('admin.services.create');
    }

    /**
     * Store a newly created service in storage.
     *
     * @return RedirectResponse
     */
    public function store(ServiceRequest $request)
    {
        $this->serviceService->create($request->validatedData(), $request);

        return redirect()
            ->route('admin.services.index')
            ->with('success', __('messages.service_created'));
    }

    /**
     * Show the form for editing the specified service.
     */
    public function edit(Service $service): View
    {
        return view('admin.services.edit', compact('service'));
    }

    /**
     * Update the specified service in storage.
     *
     * @return RedirectResponse
     */
    public function update(ServiceRequest $request, Service $service)
    {
        $this->serviceService->update($service, $request->validatedData(), $request);

        return redirect()
            ->route('admin.services.index')
            ->with('success', __('messages.service_updated'));
    }

    /**
     * Remove the specified service from storage.
     *
     * @return RedirectResponse
     */
    public function destroy(Service $service)
    {
        $this->serviceService->delete($service);

        return redirect()
            ->route('admin.services.index')
            ->with('success', __('messages.service_deleted'));
    }
}
