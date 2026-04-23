<?php

namespace Highblossom\ContentBlocks\Blocks;

use Highblossom\ContentBlocks\Services\AbstractBlock;

class ListBlock extends AbstractBlock
{
    public function getType(): string
    {
        return 'list';
    }

    public function getValidationRules(): array
    {
        return [
            'items' => 'nullable|array',
            'type' => 'required|string|in:ordered,unordered,ul,ol',
            'class' => 'nullable|string',
        ];
    }

    public function getDefaultAttributes(): array
    {
        return [
            'items' => [],
            'type' => 'unordered',
            'class' => '',
        ];
    }

    protected function getAttributeCasts(): array
    {
        return [
            'items' => 'array',
            'type' => 'string',
            'class' => 'string',
        ];
    }

    /**
     * Normalize list type to ordered/unordered.
     *
     * @param string $type
     * @return string
     */
    protected function normalizeType(string $type): string
    {
        return match (strtolower($type)) {
            'ol', 'ordered' => 'ordered',
            'ul', 'unordered' => 'unordered',
            default => 'unordered',
        };
    }

    /**
     * Get the data to pass to the view.
     *
     * @param array $attributes
     * @return array
     */
    protected function getViewData(array $attributes): array
    {
        return [
            'items' => $attributes['items'] ?? [],
            'type' => $this->normalizeType($attributes['type'] ?? 'unordered'),
            'class' => $attributes['class'] ?? '',
        ];
    }
}
