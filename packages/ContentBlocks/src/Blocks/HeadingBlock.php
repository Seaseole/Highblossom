<?php

namespace Highblossom\ContentBlocks\Blocks;

use Highblossom\ContentBlocks\Services\AbstractBlock;

class HeadingBlock extends AbstractBlock
{
    public function getType(): string
    {
        return 'heading';
    }

    public function getValidationRules(): array
    {
        return [
            'content' => 'nullable|string',
            'level' => 'required|string',
            'class' => 'nullable|string',
        ];
    }

    public function getDefaultAttributes(): array
    {
        return [
            'content' => '',
            'level' => 2,
            'class' => '',
        ];
    }

    protected function getAttributeCasts(): array
    {
        return [
            'content' => 'string',
            'class' => 'string',
        ];
    }

    /**
     * Normalize the level to integer (1-6).
     *
     * @param string|int $level
     * @return int
     */
    protected function normalizeLevel(string|int $level): int
    {
        // If it's already an integer between 1-6, use it
        if (is_int($level) && $level >= 1 && $level <= 6) {
            return $level;
        }

        // If it's a string like 'h2', extract the number
        if (is_string($level)) {
            if (preg_match('/h([1-6])/', strtolower($level), $matches)) {
                return (int) $matches[1];
            }

            // Try to parse as integer directly
            $intLevel = (int) $level;
            if ($intLevel >= 1 && $intLevel <= 6) {
                return $intLevel;
            }
        }

        // Default to h2
        return 2;
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
            'content' => $attributes['content'] ?? '',
            'level' => $this->normalizeLevel($attributes['level'] ?? 2),
            'class' => $attributes['class'] ?? '',
        ];
    }
}
