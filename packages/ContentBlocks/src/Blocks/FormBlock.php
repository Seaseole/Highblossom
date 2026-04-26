<?php

namespace Highblossom\ContentBlocks\Blocks;

use Highblossom\ContentBlocks\Services\AbstractBlock;

final class FormBlock extends AbstractBlock
{
    public function getType(): string
    {
        return 'form';
    }

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

    public function getDefaultAttributes(): array
    {
        return [
            'fields' => [],
            'submit_text' => 'Submit',
            'action_url' => null,
        ];
    }

    protected function getAttributeCasts(): array
    {
        return [
            'fields' => 'array',
            'submit_text' => 'string',
            'action_url' => 'string',
        ];
    }
}
