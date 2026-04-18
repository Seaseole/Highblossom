<?php

declare(strict_types=1);

namespace App\Domains\Content\Blocks;

final class CtaBlock extends Block
{
    public static function id(): string
    {
        return 'cta';
    }

    public static function name(): string
    {
        return 'Call to Action';
    }

    public static function icon(): string
    {
        return 'megaphone';
    }

    public static function category(): string
    {
        return 'layout';
    }

    public static function description(): string
    {
        return 'A prominent call-to-action banner with heading, text, and button.';
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
                'name' => 'text',
                'type' => 'textarea',
                'label' => 'Text',
                'required' => false,
            ],
            [
                'name' => 'button_text',
                'type' => 'text',
                'label' => 'Button Text',
                'required' => true,
            ],
            [
                'name' => 'button_url',
                'type' => 'text',
                'label' => 'Button URL',
                'required' => true,
            ],
            [
                'name' => 'style',
                'type' => 'select',
                'label' => 'Style',
                'required' => false,
                'options' => [
                    ['value' => 'default', 'label' => 'Default (White)'],
                    ['value' => 'primary', 'label' => 'Primary (Indigo)'],
                    ['value' => 'dark', 'label' => 'Dark'],
                ],
                'default' => 'primary',
            ],
        ];
    }

    public static function defaultData(): array
    {
        return [
            'heading' => 'Ready to Get Started?',
            'text' => 'Contact us today for a free consultation.',
            'button_text' => 'Contact Us',
            'button_url' => '/contact',
            'style' => 'primary',
        ];
    }

    public static function validationRules(): array
    {
        return [
            'heading' => ['required', 'string', 'max:255'],
            'text' => ['nullable', 'string'],
            'button_text' => ['required', 'string', 'max:100'],
            'button_url' => ['required', 'string', 'max:500'],
            'style' => ['nullable', 'in:default,primary,dark'],
        ];
    }

    public static function component(): string
    {
        return 'blocks.cta';
    }
}
