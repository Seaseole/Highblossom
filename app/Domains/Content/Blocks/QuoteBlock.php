<?php

declare(strict_types=1);

namespace App\Domains\Content\Blocks;

final class QuoteBlock extends Block
{
    public static function id(): string
    {
        return 'quote';
    }

    public static function name(): string
    {
        return 'Quote / Testimonial';
    }

    public static function icon(): string
    {
        return 'quote';
    }

    public static function category(): string
    {
        return 'content';
    }

    public static function description(): string
    {
        return 'A styled quote or testimonial with attribution and optional avatar.';
    }

    public static function schema(): array
    {
        return [
            [
                'name' => 'quote',
                'type' => 'textarea',
                'label' => 'Quote Text',
                'required' => true,
            ],
            [
                'name' => 'author',
                'type' => 'text',
                'label' => 'Author Name',
                'required' => false,
            ],
            [
                'name' => 'title',
                'type' => 'text',
                'label' => 'Author Title / Role',
                'required' => false,
            ],
            [
                'name' => 'avatar',
                'type' => 'image',
                'label' => 'Author Avatar',
                'required' => false,
            ],
            [
                'name' => 'style',
                'type' => 'select',
                'label' => 'Quote Style',
                'required' => false,
                'options' => [
                    ['value' => 'default', 'label' => 'Default'],
                    ['value' => 'large', 'label' => 'Large Pull Quote'],
                    ['value' => 'testimonial', 'label' => 'Testimonial Card'],
                ],
                'default' => 'default',
            ],
        ];
    }

    public static function defaultData(): array
    {
        return [
            'quote' => '',
            'author' => '',
            'title' => '',
            'avatar' => null,
            'style' => 'default',
        ];
    }

    public static function validationRules(): array
    {
        return [
            'quote' => ['required', 'string'],
            'author' => ['nullable', 'string', 'max:255'],
            'title' => ['nullable', 'string', 'max:255'],
            'avatar' => ['nullable', 'string'],
            'style' => ['nullable', 'in:default,large,testimonial'],
        ];
    }

    public static function component(): string
    {
        return 'blocks.quote';
    }
}
