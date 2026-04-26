<?php

namespace Highblossom\ContentBlocks\Blocks;

use Highblossom\ContentBlocks\Services\AbstractBlock;

final class AlertBlock extends AbstractBlock
{
    public function getType(): string
    {
        return 'alert';
    }

    public function getValidationRules(): array
    {
        return [
            'type' => 'required|in:info,success,warning,danger',
            'title' => 'nullable|string',
            'content' => 'required|string',
            'dismissible' => 'required|boolean',
        ];
    }

    public function getDefaultAttributes(): array
    {
        return [
            'type' => 'info',
            'title' => null,
            'content' => '',
            'dismissible' => false,
        ];
    }

    protected function getAttributeCasts(): array
    {
        return [
            'type' => 'string',
            'title' => 'string',
            'content' => 'string',
            'dismissible' => 'bool',
        ];
    }
}
