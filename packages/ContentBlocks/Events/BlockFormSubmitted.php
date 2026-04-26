<?php

declare(strict_types=1);

namespace Highblossom\ContentBlocks\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

final class BlockFormSubmitted
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public readonly string $blockId,
        public readonly array $data,
        public readonly ?string $actionUrl = null
    ) {}
}
