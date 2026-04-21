<?php

namespace App\Actions\Admin;

use App\Models\User;
use Spatie\Permission\Models\Role;

class AssignRoleToUser
{
    /**
     * Assign a role to a user.
     */
    public function __invoke(User $user, string|Role $role): void
    {
        $user->assignRole($role);
    }
}
