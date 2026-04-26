<?php

namespace Highblossom\ContentBlocks\Blocks;

use Highblossom\ContentBlocks\Services\AbstractBlock;
use Highblossom\ContentBlocks\Services\OEmbedResolver;

final class EmbedBlock extends AbstractBlock
{
    private OEmbedResolver $resolver;

    public function __construct(OEmbedResolver $resolver)
    {
        $this->resolver = $resolver;
    }

    public function getType(): string
    {
        return 'embed';
    }

    public function getValidationRules(): array
    {
        return [
            'url' => 'required|url',
            'title' => 'nullable|string',
        ];
    }

    public function getDefaultAttributes(): array
    {
        return [
            'url' => '',
            'title' => null,
        ];
    }

    protected function getAttributeCasts(): array
    {
        return [
            'url' => 'string',
            'title' => 'string',
        ];
    }

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
