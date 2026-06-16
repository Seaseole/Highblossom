<?php

declare(strict_types=1);

namespace Highblossom\ContentBlocks\Blocks;

use Highblossom\ContentBlocks\Services\AbstractBlock;

/**
 * List block for ordered and unordered lists.
 */
class ListBlock extends AbstractBlock
{
    /**
     * Get the block type identifier.
     */
    public function getType(): string
    {
        return 'list';
    }

    /**
     * Get the validation rules for this block.
     */
    public function getValidationRules(): array
    {
        return [
            'items' => 'nullable|array',
            'type' => 'required|string|in:ordered,unordered,ul,ol',
            'class' => 'nullable|string',
        ];
    }

    /**
     * Get the default attributes for this block.
     */
    public function getDefaultAttributes(): array
    {
        return [
            'items' => [],
            'type' => 'unordered',
            'class' => '',
        ];
    }

    /**
     * Get the attribute type casts.
     */
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
