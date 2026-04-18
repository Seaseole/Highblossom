<?php

declare(strict_types=1);

namespace App\Domains\Content\Observers;

use App\Domains\Content\Models\Post;
use App\Domains\Content\Actions\Posts\ClearPostCache;

final class PostObserver
{
    public function __construct(
        private readonly ClearPostCache $clearPostCache
    ) {}

    public function saved(Post $post): void
    {
        $this->clearPostCache->execute($post);
        $this->clearPostCache->clearAdminList();
    }

    public function deleted(Post $post): void
    {
        $this->clearPostCache->execute($post);
        $this->clearPostCache->clearAdminList();
    }

    public function restored(Post $post): void
    {
        $this->clearPostCache->execute($post);
        $this->clearPostCache->clearAdminList();
    }

    public function forceDeleted(Post $post): void
    {
        $this->clearPostCache->execute($post);
        $this->clearPostCache->clearAdminList();
    }
}
