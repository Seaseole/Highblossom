<?php

declare(strict_types=1);

namespace App\Domains\Content\Blocks;

final class RichTextBlock extends Block
{
    public static function id(): string
    {
        return 'rich-text';
    }

    public static function name(): string
    {
        return 'Rich Text';
    }

    public static function icon(): string
    {
        return 'type';
    }

    public static function category(): string
    {
        return 'content';
    }

    public static function description(): string
    {
        return 'A rich text editor for formatted content with headings, lists, and links.';
    }

    public static function schema(): array
    {
        return [
            [
                'name' => 'content',
                'type' => 'rich-text',
                'label' => 'Content',
                'required' => true,
                'help' => 'Write your content here. Use formatting tools for styling.',
            ],
        ];
    }

    public static function defaultData(): array
    {
        return [
            'content' => '<p>Start writing your content here...</p>',
        ];
    }

    public static function validationRules(): array
    {
        return [
            'content' => ['required', 'string'],
        ];
    }

    public static function component(): string
    {
        return 'blocks.rich-text';
    }
}
