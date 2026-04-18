<?php

declare(strict_types=1);

namespace App\Domains\Content\Policies;

use App\Domains\Content\Models\Post;
use App\Models\User;

final class PostPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Post $post): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->hasVerifiedEmail();
    }

    public function update(User $user, Post $post): bool
    {
        return $user->hasVerifiedEmail();
    }

    public function delete(User $user, Post $post): bool
    {
        return $user->hasVerifiedEmail();
    }

    public function restore(User $user, Post $post): bool
    {
        return $user->hasVerifiedEmail();
    }

    public function forceDelete(User $user, Post $post): bool
    {
        return false;
    }

    public function publish(User $user, Post $post): bool
    {
        return $user->hasVerifiedEmail();
    }
}
