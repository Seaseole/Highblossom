<?php

namespace Highblossom\ContentBlocks\Blocks;

use Highblossom\ContentBlocks\Services\AbstractBlock;
use Highblossom\ContentBlocks\Services\HtmlSanitizer;

final class HtmlBlock extends AbstractBlock
{
    private HtmlSanitizer $sanitizer;

    public function __construct(HtmlSanitizer $sanitizer)
    {
        $this->sanitizer = $sanitizer;
    }

    public function getType(): string
    {
        return 'html';
    }

    public function getValidationRules(): array
    {
        return [
            'content' => 'required|string',
        ];
    }

    public function getDefaultAttributes(): array
    {
        return [
            'content' => '',
        ];
    }

    protected function getAttributeCasts(): array
    {
        return [
            'content' => 'string',
        ];
    }

    public function normalizeAttributes(array $attributes): array
    {
        $attributes = parent::normalizeAttributes($attributes);

        if (isset($attributes['content'])) {
            $attributes['content'] = $this->sanitizer->sanitize($attributes['content']);
        }

        return $attributes;
    }
}
