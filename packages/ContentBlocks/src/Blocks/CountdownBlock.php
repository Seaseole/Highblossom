<?php

namespace Highblossom\ContentBlocks\Blocks;

use Highblossom\ContentBlocks\Services\AbstractBlock;

final class CountdownBlock extends AbstractBlock
{
    public function getType(): string
    {
        return 'countdown';
    }

    public function getValidationRules(): array
    {
        return [
            'target_date' => 'required|date',
            'label' => 'nullable|string',
            'timezone' => 'nullable|string',
        ];
    }

    public function getDefaultAttributes(): array
    {
        return [
            'target_date' => now()->addDays(7)->toIso8601String(),
            'label' => null,
            'timezone' => config('app.timezone', 'UTC'),
        ];
    }

    protected function getAttributeCasts(): array
    {
        return [
            'target_date' => 'string',
            'label' => 'string',
            'timezone' => 'string',
        ];
    }
}
