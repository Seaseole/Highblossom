<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\ServiceTypeRequest;
use App\Models\ServiceType;
use App\Services\ServiceTypeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/**
 * Manage CRUD operations for service types.
 */
final class ServiceTypeController
{
    public function __construct(
        private readonly ServiceTypeService $serviceTypeService,
    ) {}

    /**
     * Display a paginated list of service types.
     */
    public function index(): View
    {
        $serviceTypes = ServiceType::query()->ordered()->paginate(15);

        return view('admin.service-types.index', compact('serviceTypes'));
    }

    /**
     * Show the form for creating a new service type.
     */
    public function create(): View
    {
        return view('admin.service-types.create');
    }

    /**
     * Store a newly created service type in storage.
     */
    public function store(ServiceTypeRequest $request): RedirectResponse
    {
        $this->serviceTypeService->create($request->validated());

        return redirect()
            ->route('admin.service-types.index')
            ->with('success', __('messages.service_type_created'));
    }

    /**
     * Show the form for editing the specified service type.
     */
    public function edit(ServiceType $serviceType): View
    {
        return view('admin.service-types.edit', compact('serviceType'));
    }

    /**
     * Update the specified service type in storage.
     */
    public function update(ServiceTypeRequest $request, ServiceType $serviceType): RedirectResponse
    {
        $this->serviceTypeService->update($serviceType, $request->validated());

        return redirect()
            ->route('admin.service-types.index')
            ->with('success', __('messages.service_type_updated'));
    }

    /**
     * Remove the specified service type from storage.
     */
    public function destroy(ServiceType $serviceType): RedirectResponse
    {
        $this->serviceTypeService->delete($serviceType);

        return redirect()
            ->route('admin.service-types.index')
            ->with('success', __('messages.service_type_deleted'));
    }
}
