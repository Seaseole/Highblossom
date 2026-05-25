<?php

declare(strict_types=1);

namespace App\Services;

use Spatie\Permission\Models\Role;

final class RoleService
{
    public function create(array $data): Role
    {
        $role = Role::create(['name' => $data['name']]);

        if (! empty($data['permissions'])) {
            $role->syncPermissions($data['permissions']);
        }

        return $role;
    }

    public function update(Role $role, array $data): Role
    {
        $role->update(['name' => $data['name']]);

        if (isset($data['permissions'])) {
            $role->syncPermissions($data['permissions']);
        }

        return $role->fresh();
    }

    public function delete(Role $role): bool
    {
        if ($role->name === 'Super Admin') {
            return false;
        }

        $role->delete();

        return true;
    }
}
