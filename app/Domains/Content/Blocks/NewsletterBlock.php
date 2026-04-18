<?php

declare(strict_types=1);

namespace App\Domains\Content\Blocks;

final class NewsletterBlock extends Block
{
    public static function id(): string
    {
        return 'newsletter';
    }

    public static function name(): string
    {
        return 'Newsletter Signup';
    }

    public static function icon(): string
    {
        return 'mail';
    }

    public static function category(): string
    {
        return 'layout';
    }

    public static function description(): string
    {
        return 'Email subscription form with customizable CTA.';
    }

    public static function schema(): array
    {
        return [
            [
                'name' => 'heading',
                'type' => 'text',
                'label' => 'Heading',
                'required' => true,
                'default' => 'Stay Updated',
            ],
            [
                'name' => 'description',
                'type' => 'textarea',
                'label' => 'Description',
                'required' => false,
                'default' => 'Subscribe to our newsletter for the latest updates.',
            ],
            [
                'name' => 'button_text',
                'type' => 'text',
                'label' => 'Button Text',
                'required' => true,
                'default' => 'Subscribe',
            ],
            [
                'name' => 'style',
                'type' => 'select',
                'label' => 'Style',
                'required' => false,
                'options' => [
                    ['value' => 'inline', 'label' => 'Inline (Horizontal)'],
                    ['value' => 'stacked', 'label' => 'Stacked (Vertical)'],
                ],
                'default' => 'inline',
            ],
            [
                'name' => 'background',
                'type' => 'select',
                'label' => 'Background',
                'required' => false,
                'options' => [
                    ['value' => 'default', 'label' => 'Default'],
                    ['value' => 'primary', 'label' => 'Primary'],
                    ['value' => 'dark', 'label' => 'Dark'],
                ],
                'default' => 'primary',
            ],
        ];
    }

    public static function defaultData(): array
    {
        return [
            'heading' => 'Stay Updated',
            'description' => 'Subscribe to our newsletter for the latest updates.',
            'button_text' => 'Subscribe',
            'style' => 'inline',
            'background' => 'primary',
        ];
    }

    public static function validationRules(): array
    {
        return [
            'heading' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'button_text' => ['required', 'string', 'max:100'],
            'style' => ['nullable', 'in:inline,stacked'],
            'background' => ['nullable', 'in:default,primary,dark'],
        ];
    }

    public static function component(): string
    {
        return 'blocks.newsletter';
    }
}
