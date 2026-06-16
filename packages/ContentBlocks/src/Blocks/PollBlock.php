<?php

declare(strict_types=1);

namespace Highblossom\ContentBlocks\Blocks;

use Highblossom\ContentBlocks\Services\AbstractBlock;

/**
 * Poll block with voting options.
 */
final class PollBlock extends AbstractBlock
{
    /**
     * Get the block type identifier.
     */
    public function getType(): string
    {
        return 'poll';
    }

    /**
     * Get the validation rules for this block.
     */
    public function getValidationRules(): array
    {
        return [
            'poll_id' => 'nullable|integer',
            'question' => 'required|string',
            'options' => 'required|array|min:2',
            'options.*' => 'required|string',
            'allow_multiple' => 'required|boolean',
            'show_results' => 'required|boolean',
        ];
    }

    /**
     * Get the default attributes for this block.
     */
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

    /**
     * Get the attribute type casts.
     */
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
