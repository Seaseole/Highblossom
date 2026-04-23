<?php

namespace Highblossom\ContentBlocks\Blocks;

use Highblossom\ContentBlocks\Services\AbstractBlock;

class CTABlock extends AbstractBlock
{
    public function getType(): string
    {
        return 'cta';
    }

    public function getValidationRules(): array
    {
        return [
            'title' => 'nullable|string',
            'description' => 'nullable|string',
            'button_text' => 'nullable|string',
            'button_url' => 'nullable|string',
            'class' => 'nullable|string',
        ];
    }

    public function getDefaultAttributes(): array
    {
        return [
            'title' => '',
            'description' => '',
            'button_text' => '',
            'button_url' => '',
            'class' => '',
        ];
    }

    protected function getAttributeCasts(): array
    {
        return [
            'title' => 'string',
            'description' => 'string',
            'button_text' => 'string',
            'button_url' => 'string',
            'class' => 'string',
        ];
    }

    protected function getViewName(): string
    {
        return 'cta';
    }
}
