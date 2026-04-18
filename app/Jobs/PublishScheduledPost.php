<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Domains\Content\Actions\Posts\PublishPost;
use App\Domains\Content\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

final class PublishScheduledPost implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private readonly int $postId
    ) {}

    public function handle(): void
    {
        $post = Post::find($this->postId);

        if (! $post || $post->is_published) {
            return;
        }

        // Check if scheduled time has been reached
        if ($post->published_at && $post->published_at->isPast()) {
            $action = new PublishPost();
            $action->execute($post);
        }
    }
}
