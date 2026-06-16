<?php

declare(strict_types=1);

namespace Highblossom\ContentBlocks\Blocks;

use Highblossom\ContentBlocks\Services\AbstractBlock;

/**
 * Countdown block for displaying a countdown timer.
 */
final class CountdownBlock extends AbstractBlock
{
    /**
     * Get the block type identifier.
     */
    public function getType(): string
    {
        return 'countdown';
    }

    /**
     * Get the validation rules for this block.
     */
    public function getValidationRules(): array
    {
        return [
            'target_date' => 'required|date',
            'label' => 'nullable|string',
            'timezone' => 'nullable|string',
        ];
    }

    /**
     * Get the default attributes for this block.
     */
    public function getDefaultAttributes(): array
    {
        return [
            'target_date' => now()->addDays(7)->toIso8601String(),
            'label' => null,
            'timezone' => config('app.timezone', 'UTC'),
        ];
    }

    /**
     * Get the attribute type casts.
     */
    protected function getAttributeCasts(): array
    {
        return [
            'target_date' => 'string',
            'label' => 'string',
            'timezone' => 'string',
        ];
    }
}
