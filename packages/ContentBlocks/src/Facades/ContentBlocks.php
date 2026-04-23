<?php

namespace Highblossom\ContentBlocks\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Highblossom\ContentBlocks\Services\BlockRegistry register(string $type, \Highblossom\ContentBlocks\Contracts\BlockInterface $block)
 * @method static \Highblossom\ContentBlocks\Contracts\BlockInterface|null get(string $type)
 * @method static bool has(string $type)
 * @method static \Illuminate\Support\Collection all()
 * @method static array types()
 * @method static string render(string $type, array $attributes = [])
 *
 * @see \Highblossom\ContentBlocks\Services\BlockRegistry
 */
class ContentBlocks extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return \Highblossom\ContentBlocks\Services\BlockRegistry::class;
    }
}
