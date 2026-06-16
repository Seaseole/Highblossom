<?php

declare(strict_types=1);

namespace Highblossom\ContentBlocks\Blocks;

use Highblossom\ContentBlocks\Services\AbstractBlock;
use Highblossom\ContentBlocks\Services\OEmbedResolver;

/**
 * Embed block for oEmbed-powered media embeds.
 */
final class EmbedBlock extends AbstractBlock
{
    private OEmbedResolver $resolver;

    /**
     * Create a new embed block instance.
     */
    public function __construct(OEmbedResolver $resolver)
    {
        $this->resolver = $resolver;
    }

    /**
     * Get the block type identifier.
     */
    public function getType(): string
    {
        return 'embed';
    }

    /**
     * Get the validation rules for this block.
     */
    public function getValidationRules(): array
    {
        return [
            'url' => 'required|url',
            'title' => 'nullable|string',
        ];
    }

    /**
     * Get the default attributes for this block.
     */
    public function getDefaultAttributes(): array
    {
        return [
            'url' => '',
            'title' => null,
        ];
    }

    /**
     * Get the attribute type casts.
     */
    protected function getAttributeCasts(): array
    {
        return [
            'url' => 'string',
            'title' => 'string',
        ];
    }

    /**
     * Normalize attributes by resolving oEmbed data.
     */
    public function normalizeAttributes(array $attributes): array
    {
        $attributes = parent::normalizeAttributes($attributes);

        if (isset($attributes['url'])) {
            $embedData = $this->resolver->resolve($attributes['url']);

            if ($embedData) {
                $attributes['embed_html'] = $embedData['html'];
                $attributes['embed_title'] = $embedData['title'];
                $attributes['embed_thumbnail'] = $embedData['thumbnail_url'];
                $attributes['embed_width'] = $embedData['width'];
                $attributes['embed_height'] = $embedData['height'];
                $attributes['embed_type'] = $embedData['type'];
                $attributes['embed_provider'] = $embedData['provider'];
            }
        }

        return $attributes;
    }
}
