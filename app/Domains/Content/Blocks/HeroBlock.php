<?php

declare(strict_types=1);

namespace App\Domains\Content\Blocks;

final class HeroBlock extends Block
{
    public static function id(): string
    {
        return 'hero';
    }

    public static function name(): string
    {
        return 'Hero Section';
    }

    public static function icon(): string
    {
        return 'layout-template';
    }

    public static function category(): string
    {
        return 'layout';
    }

    public static function description(): string
    {
        return 'A full-width hero section with heading, subheading, image, and call-to-action.';
    }

    public static function schema(): array
    {
        return [
            [
                'name' => 'heading',
                'type' => 'text',
                'label' => 'Heading',
                'required' => true,
            ],
            [
                'name' => 'subheading',
                'type' => 'textarea',
                'label' => 'Subheading',
                'required' => false,
            ],
            [
                'name' => 'image',
                'type' => 'image',
                'label' => 'Background Image',
                'required' => false,
            ],
            [
                'name' => 'cta_text',
                'type' => 'text',
                'label' => 'Button Text',
                'required' => false,
            ],
            [
                'name' => 'cta_url',
                'type' => 'text',
                'label' => 'Button URL',
                'required' => false,
            ],
            [
                'name' => 'alignment',
                'type' => 'select',
                'label' => 'Text Alignment',
                'required' => false,
                'options' => [
                    ['value' => 'left', 'label' => 'Left'],
                    ['value' => 'center', 'label' => 'Center'],
                    ['value' => 'right', 'label' => 'Right'],
                ],
                'default' => 'center',
            ],
        ];
    }

    public static function defaultData(): array
    {
        return [
            'heading' => 'Welcome to Our Site',
            'subheading' => '',
            'image' => null,
            'cta_text' => 'Learn More',
            'cta_url' => '#',
            'alignment' => 'center',
        ];
    }

    public static function validationRules(): array
    {
        return [
            'heading' => ['required', 'string', 'max:255'],
            'subheading' => ['nullable', 'string'],
            'image' => ['nullable', 'string'],
            'cta_text' => ['nullable', 'string', 'max:100'],
            'cta_url' => ['nullable', 'string', 'max:500'],
            'alignment' => ['nullable', 'in:left,center,right'],
        ];
    }

    public static function component(): string
    {
        return 'blocks.hero';
    }
}
