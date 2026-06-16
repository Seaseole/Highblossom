<?php

declare(strict_types=1);

namespace Highblossom\ContentBlocks\Blocks;

use Highblossom\ContentBlocks\Services\AbstractBlock;

/**
 * Form block for building dynamic forms.
 */
final class FormBlock extends AbstractBlock
{
    /**
     * Get the block type identifier.
     */
    public function getType(): string
    {
        return 'form';
    }

    /**
     * Get the validation rules for this block.
     */
    public function getValidationRules(): array
    {
        return [
            'fields' => 'required|array|min:1',
            'fields.*.name' => 'required|string',
            'fields.*.label' => 'required|string',
            'fields.*.type' => 'required|in:text,email,textarea,select,checkbox,radio',
            'fields.*.required' => 'required|boolean',
            'fields.*.options' => 'nullable|array',
            'submit_text' => 'required|string',
            'action_url' => 'nullable|url',
        ];
    }

    /**
     * Get the default attributes for this block.
     */
    public function getDefaultAttributes(): array
    {
        return [
            'fields' => [],
            'submit_text' => 'Submit',
            'action_url' => null,
        ];
    }

    /**
     * Get the attribute type casts.
     */
    protected function getAttributeCasts(): array
    {
        return [
            'fields' => 'array',
            'submit_text' => 'string',
            'action_url' => 'string',
        ];
    }
}
