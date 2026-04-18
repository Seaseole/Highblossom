<?php

declare(strict_types=1);

namespace App\Domains\Content\Blocks;

final class SpacerBlock extends Block
{
    public static function id(): string
    {
        return 'spacer';
    }

    public static function name(): string
    {
        return 'Spacer';
    }

    public static function icon(): string
    {
        return 'move-vertical';
    }

    public static function category(): string
    {
        return 'layout';
    }

    public static function description(): string
    {
        return 'Add vertical spacing between sections with configurable height.';
    }

    public static function schema(): array
    {
        return [
            [
                'name' => 'height',
                'type' => 'select',
                'label' => 'Spacing Height',
                'required' => true,
                'options' => [
                    ['value' => 'small', 'label' => 'Small (1rem / 16px)'],
                    ['value' => 'medium', 'label' => 'Medium (2rem / 32px)'],
                    ['value' => 'large', 'label' => 'Large (4rem / 64px)'],
                    ['value' => 'xl', 'label' => 'Extra Large (6rem / 96px)'],
                ],
                'default' => 'medium',
            ],
        ];
    }

    public static function defaultData(): array
    {
        return [
            'height' => 'medium',
        ];
    }

    public static function validationRules(): array
    {
        return [
            'height' => ['required', 'in:small,medium,large,xl'],
        ];
    }

    public static function component(): string
    {
        return 'blocks.spacer';
    }
}
