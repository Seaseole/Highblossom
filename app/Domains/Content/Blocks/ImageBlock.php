<?php

declare(strict_types=1);

namespace App\Domains\Content\Blocks;

final class ImageBlock extends Block
{
    public static function id(): string
    {
        return 'image';
    }

    public static function name(): string
    {
        return 'Single Image';
    }

    public static function icon(): string
    {
        return 'image';
    }

    public static function category(): string
    {
        return 'media';
    }

    public static function description(): string
    {
        return 'A single image with optional caption and alignment options.';
    }

    public static function schema(): array
    {
        return [
            [
                'name' => 'image',
                'type' => 'image',
                'label' => 'Image',
                'required' => true,
            ],
            [
                'name' => 'alt',
                'type' => 'text',
                'label' => 'Alt Text',
                'required' => false,
                'help' => 'Describe the image for accessibility and SEO.',
            ],
            [
                'name' => 'caption',
                'type' => 'text',
                'label' => 'Caption',
                'required' => false,
            ],
            [
                'name' => 'alignment',
                'type' => 'select',
                'label' => 'Alignment',
                'required' => false,
                'options' => [
                    ['value' => 'left', 'label' => 'Left'],
                    ['value' => 'center', 'label' => 'Center'],
                    ['value' => 'right', 'label' => 'Right'],
                    ['value' => 'full', 'label' => 'Full Width'],
                ],
                'default' => 'center',
            ],
        ];
    }

    public static function defaultData(): array
    {
        return [
            'image' => null,
            'alt' => '',
            'caption' => '',
            'alignment' => 'center',
        ];
    }

    public static function validationRules(): array
    {
        return [
            'image' => ['required', 'string'],
            'alt' => ['nullable', 'string', 'max:255'],
            'caption' => ['nullable', 'string', 'max:500'],
            'alignment' => ['nullable', 'in:left,center,right,full'],
        ];
    }

    public static function component(): string
    {
        return 'blocks.image';
    }
}
