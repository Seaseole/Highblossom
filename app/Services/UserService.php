<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use Illuminate\Container\Attributes\Singleton;

#[Singleton(name: 'users')]
final class UserService
{
    public function __construct(
        private readonly UserSessionService $userSessions,
    ) {}

    public function create(array $data): User
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);

        if (! empty($data['roles'])) {
            $user->syncRoles($data['roles']);
        }

        return $user;
    }

    /**
     * Update a user's details, revoking their sessions when an admin sets a new password.
     */
    public function update(User $user, array $data): User
    {
        $user->update([
            'name' => $data['name'],
            'email' => $data['email'],
        ]);

        if (! empty($data['password'])) {
            $user->update(['password' => bcrypt($data['password'])]);

            // The acting admin's own session is not one of this user's, so this
            // closes every device the target user is signed in on.
            $this->userSessions->handlePasswordChanged(
                $user,
                session()->getId(),
                auth()->user(),
            );
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
