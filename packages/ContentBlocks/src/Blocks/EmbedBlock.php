<?php

declare(strict_types=1);

namespace Highblossom\ContentBlocks\Blocks;

use Highblossom\ContentBlocks\Services\AbstractBlock;
use Highblossom\ContentBlocks\Services\HtmlSanitizer;
use Highblossom\ContentBlocks\Services\OEmbedResolver;

/**
 * Embed block for oEmbed-powered media embeds.
 */
final class EmbedBlock extends AbstractBlock
{
    private OEmbedResolver $resolver;

    private HtmlSanitizer $sanitizer;

    /**
     * Create a new embed block instance.
     */
    public function __construct(OEmbedResolver $resolver, HtmlSanitizer $sanitizer)
    {
        $this->resolver = $resolver;
        $this->sanitizer = $sanitizer;
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
            'url' => 'required|url|max:2048',
            'title' => 'nullable|string|max:255',
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
                $attributes['embed_html'] = $this->sanitizer->sanitizeEmbed((string) ($embedData['html'] ?? ''));
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
