<?php

declare(strict_types=1);

namespace App\Actions\Admin;

use App\Models\User;
use Spatie\Permission\Models\Role;

/**
 * Assign a Spatie permission role to a user.
 */
final class AssignRoleToUser
{
    /**
     * Assign a role to a user.
     */
    public function __invoke(User $user, string|Role $role): void
    {
        $user->assignRole($role);
    }
}
