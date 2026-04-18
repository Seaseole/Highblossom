<?php

declare(strict_types=1);

namespace App\Domains\Content\Blocks;

final class FeaturesBlock extends Block
{
    public static function id(): string
    {
        return 'features';
    }

    public static function name(): string
    {
        return 'Features Grid';
    }

    public static function icon(): string
    {
        return 'squares-2x2';
    }

    public static function category(): string
    {
        return 'layout';
    }

    public static function description(): string
    {
        return 'Display a grid of feature cards with icons, titles, and descriptions.';
    }

    public static function schema(): array
    {
        return [
            [
                'name' => 'heading',
                'type' => 'text',
                'label' => 'Section Heading',
                'required' => false,
            ],
            [
                'name' => 'subheading',
                'type' => 'text',
                'label' => 'Section Subheading',
                'required' => false,
            ],
            [
                'name' => 'columns',
                'type' => 'select',
                'label' => 'Number of Columns',
                'required' => false,
                'options' => [
                    ['value' => '2', 'label' => '2 Columns'],
                    ['value' => '3', 'label' => '3 Columns'],
                    ['value' => '4', 'label' => '4 Columns'],
                ],
                'default' => '3',
            ],
            [
                'name' => 'features',
                'type' => 'repeater',
                'label' => 'Features',
                'required' => true,
                'fields' => [
                    ['name' => 'icon', 'type' => 'text', 'label' => 'Icon (Flux icon name)'],
                    ['name' => 'title', 'type' => 'text', 'label' => 'Title'],
                    ['name' => 'description', 'type' => 'textarea', 'label' => 'Description'],
                ],
            ],
        ];
    }

    public static function defaultData(): array
    {
        return [
            'heading' => 'Our Features',
            'subheading' => 'What makes us different',
            'columns' => '3',
            'features' => [
                ['icon' => 'check-circle', 'title' => 'Quality Service', 'description' => 'We deliver excellence in every project.'],
                ['icon' => 'clock', 'title' => 'On Time', 'description' => 'We respect your time and deadlines.'],
                ['icon' => 'shield-check', 'title' => 'Reliable', 'description' => 'Trusted by hundreds of clients.'],
            ],
        ];
    }

    public static function validationRules(): array
    {
        return [
            'heading' => ['nullable', 'string', 'max:255'],
            'subheading' => ['nullable', 'string', 'max:255'],
            'columns' => ['nullable', 'in:2,3,4'],
            'features' => ['required', 'array', 'min:1'],
            'features.*.icon' => ['required', 'string'],
            'features.*.title' => ['required', 'string', 'max:255'],
            'features.*.description' => ['required', 'string'],
        ];
    }

    public static function component(): string
    {
        return 'blocks.features';
    }
}
