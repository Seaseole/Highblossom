<?php

declare(strict_types=1);

namespace App\Domains\Content\Policies;

use App\Domains\Content\Models\Post;
use App\Models\User;

final class PostPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view blog');
    }

    public function view(User $user, Post $post): bool
    {
        return $user->can('view blog');
    }

    public function create(User $user): bool
    {
        return $user->can('create blog');
    }

    public function update(User $user, Post $post): bool
    {
        return $user->can('update blog');
    }

    public function delete(User $user, Post $post): bool
    {
        return $user->can('delete blog');
    }

    public function restore(User $user, Post $post): bool
    {
        return $user->can('manage revisions');
    }

    public function forceDelete(User $user, Post $post): bool
    {
        return false;
    }

    public function publish(User $user, Post $post): bool
    {
        return $user->can('publish blog');
    }
}
