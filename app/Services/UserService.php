<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;

final class UserService
{
    public function create(array $data): User
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);

        if (!empty($data['roles'])) {
            $user->syncRoles($data['roles']);
        }

        return $user;
    }

    public function update(User $user, array $data): User
    {
        $user->update([
            'name' => $data['name'],
            'email' => $data['email'],
        ]);

        if (!empty($data['password'])) {
            $user->update(['password' => bcrypt($data['password'])]);
        }

        if (isset($data['roles'])) {
            $user->syncRoles($data['roles']);
        }

        return $user->fresh();
    }

    public function delete(User $user): void
    {
        $user->delete();
    }
}
