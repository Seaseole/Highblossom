<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\RoleRequest;
use App\Services\RoleService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

final class RoleController
{
    public function __construct(
        private readonly RoleService $roleService,
    ) {}

    public function index(): View
    {
        $roles = Role::with('permissions')->latest()->get();
        $permissions = Permission::all();

        return view('admin.roles.index', compact('roles', 'permissions'));
    }

    public function create(): View
    {
        $permissions = Permission::all();
        return view('admin.roles.create', compact('permissions'));
    }

    public function store(RoleRequest $request): RedirectResponse
    {
        $this->roleService->create($request->validated());

        return redirect()
            ->route('admin.roles.index')
            ->with('success', __('messages.role_created'));
    }

    public function edit(Role $role): View
    {
        $permissions = Permission::all();
        $role->load('permissions');
        return view('admin.roles.edit', compact('role', 'permissions'));
    }

    public function update(RoleRequest $request, Role $role): RedirectResponse
    {
        $this->roleService->update($role, $request->validated());

        return redirect()
            ->route('admin.roles.index')
            ->with('success', __('messages.role_updated'));
    }

    public function destroy(Role $role): RedirectResponse
    {
        $success = $this->roleService->delete($role);

        if (!$success) {
            return redirect()
                ->route('admin.roles.index')
                ->with('error', __('messages.unauthorized'));
        }

        return redirect()
            ->route('admin.roles.index')
            ->with('success', __('messages.role_deleted'));
    }
}
