<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\ServiceTypeRequest;
use App\Models\ServiceType;
use App\Services\ServiceTypeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

final class ServiceTypeController
{
    public function __construct(
        private readonly ServiceTypeService $serviceTypeService,
    ) {}

    public function index(): View
    {
        $serviceTypes = ServiceType::query()->ordered()->paginate(15);

        return view('admin.service-types.index', compact('serviceTypes'));
    }

    public function create(): View
    {
        return view('admin.service-types.create');
    }

    public function store(ServiceTypeRequest $request): RedirectResponse
    {
        $this->serviceTypeService->create($request->validated());

        return redirect()
            ->route('admin.service-types.index')
            ->with('success', __('messages.service_type_created'));
    }

    public function edit(ServiceType $serviceType): View
    {
        return view('admin.service-types.edit', compact('serviceType'));
    }

    public function update(ServiceTypeRequest $request, ServiceType $serviceType): RedirectResponse
    {
        $this->serviceTypeService->update($serviceType, $request->validated());

        return redirect()
            ->route('admin.service-types.index')
            ->with('success', __('messages.service_type_updated'));
    }

    public function destroy(ServiceType $serviceType): RedirectResponse
    {
        $this->serviceTypeService->delete($serviceType);

        return redirect()
            ->route('admin.service-types.index')
            ->with('success', __('messages.service_type_deleted'));
    }
}
