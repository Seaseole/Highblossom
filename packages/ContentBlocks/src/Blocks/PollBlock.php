<?php

namespace Highblossom\ContentBlocks\Blocks;

use Highblossom\ContentBlocks\Services\AbstractBlock;

final class PollBlock extends AbstractBlock
{
    public function getType(): string
    {
        return 'poll';
    }

    public function getValidationRules(): array
    {
        return [
            'poll_id' => 'required|integer',
            'question' => 'required|string',
            'options' => 'required|array|min:2',
            'options.*' => 'required|string',
            'allow_multiple' => 'required|boolean',
            'show_results' => 'required|boolean',
        ];
    }

    public function getDefaultAttributes(): array
    {
        return [
            'poll_id' => null,
            'question' => '',
            'options' => ['Option 1', 'Option 2'],
            'allow_multiple' => false,
            'show_results' => false,
        ];
    }

    protected function getAttributeCasts(): array
    {
        return [
            'poll_id' => 'int',
            'question' => 'string',
            'options' => 'array',
            'allow_multiple' => 'bool',
            'show_results' => 'bool',
        ];
    }
}
