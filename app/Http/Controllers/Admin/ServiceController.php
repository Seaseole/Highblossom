<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\ServiceRequest;
use App\Models\Service;
use App\Services\ServiceService;
use Illuminate\View\View;

final class ServiceController
{
    public function __construct(
        private readonly ServiceService $serviceService,
    ) {}

    public function index(): View
    {
        $services = Service::query()->latest()->paginate(15);

        return view('admin.services.index', compact('services'));
    }

    public function create(): View
    {
        return view('admin.services.create');
    }

    public function store(ServiceRequest $request)
    {
        $this->serviceService->create($request->validatedData(), $request);

        return redirect()
            ->route('admin.services.index')
            ->with('success', __('messages.service_created'));
    }

    public function edit(Service $service): View
    {
        return view('admin.services.edit', compact('service'));
    }

    public function update(ServiceRequest $request, Service $service)
    {
        $this->serviceService->update($service, $request->validatedData(), $request);

        return redirect()
            ->route('admin.services.index')
            ->with('success', __('messages.service_updated'));
    }

    public function destroy(Service $service)
    {
        $this->serviceService->delete($service);

        return redirect()
            ->route('admin.services.index')
            ->with('success', __('messages.service_deleted'));
    }
}
