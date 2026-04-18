<?php

declare(strict_types=1);

namespace App\Domains\Content\Blocks;

final class GalleryBlock extends Block
{
    public static function id(): string
    {
        return 'gallery';
    }

    public static function name(): string
    {
        return 'Image Gallery';
    }

    public static function icon(): string
    {
        return 'photo';
    }

    public static function category(): string
    {
        return 'media';
    }

    public static function description(): string
    {
        return 'Display a grid of images with captions.';
    }

    public static function schema(): array
    {
        return [
            [
                'name' => 'columns',
                'type' => 'select',
                'label' => 'Columns',
                'required' => false,
                'options' => [
                    ['value' => '2', 'label' => '2 Columns'],
                    ['value' => '3', 'label' => '3 Columns'],
                    ['value' => '4', 'label' => '4 Columns'],
                ],
                'default' => '3',
            ],
            [
                'name' => 'gap',
                'type' => 'select',
                'label' => 'Gap',
                'required' => false,
                'options' => [
                    ['value' => 'small', 'label' => 'Small'],
                    ['value' => 'medium', 'label' => 'Medium'],
                    ['value' => 'large', 'label' => 'Large'],
                ],
                'default' => 'medium',
            ],
            [
                'name' => 'images',
                'type' => 'repeater',
                'label' => 'Images',
                'required' => true,
                'fields' => [
                    ['name' => 'url', 'type' => 'text', 'label' => 'Image URL'],
                    ['name' => 'caption', 'type' => 'text', 'label' => 'Caption'],
                    ['name' => 'alt', 'type' => 'text', 'label' => 'Alt Text'],
                ],
            ],
        ];
    }

    public static function defaultData(): array
    {
        return [
            'columns' => '3',
            'gap' => 'medium',
            'images' => [
                ['url' => '', 'caption' => '', 'alt' => ''],
            ],
        ];
    }

    public static function validationRules(): array
    {
        return [
            'columns' => ['nullable', 'in:2,3,4'],
            'gap' => ['nullable', 'in:small,medium,large'],
            'images' => ['required', 'array', 'min:1'],
            'images.*.url' => ['required', 'url'],
            'images.*.caption' => ['nullable', 'string', 'max:255'],
            'images.*.alt' => ['nullable', 'string', 'max:255'],
        ];
    }

    public static function component(): string
    {
        return 'blocks.gallery';
    }
}
