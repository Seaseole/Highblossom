<?php

declare(strict_types=1);

namespace App\Domains\Content\Blocks;

final class VideoBlock extends Block
{
    public static function id(): string
    {
        return 'video';
    }

    public static function name(): string
    {
        return 'Video Embed';
    }

    public static function icon(): string
    {
        return 'play-circle';
    }

    public static function category(): string
    {
        return 'media';
    }

    public static function description(): string
    {
        return 'Embed YouTube or Vimeo videos with lazy loading.';
    }

    public static function schema(): array
    {
        return [
            [
                'name' => 'url',
                'type' => 'text',
                'label' => 'Video URL',
                'required' => true,
                'help' => 'Paste a YouTube or Vimeo URL',
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
            'url' => '',
            'caption' => '',
            'alignment' => 'center',
        ];
    }

    public static function validationRules(): array
    {
        return [
            'url' => ['required', 'url'],
            'caption' => ['nullable', 'string', 'max:500'],
            'alignment' => ['nullable', 'in:left,center,right,full'],
        ];
    }

    public static function component(): string
    {
        return 'blocks.video';
    }
}
