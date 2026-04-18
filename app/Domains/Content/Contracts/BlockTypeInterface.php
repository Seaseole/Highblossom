<?php

declare(strict_types=1);

namespace App\Domains\Content\Contracts;

interface BlockTypeInterface
{
    /**
     * Unique identifier for the block type.
     */
    public static function id(): string;

    /**
     * Human-readable display name.
     */
    public static function name(): string;

    /**
     * Flux icon name for the block.
     */
    public static function icon(): string;

    /**
     * Category for grouping in the block library.
     * e.g., 'layout', 'content', 'media', 'blog'
     */
    public static function category(): string;

    /**
     * Description of what this block does.
     */
    public static function description(): string;

    /**
     * Form schema for the editor - array of field definitions.
     * Each field: ['name' => 'heading', 'type' => 'text', 'label' => 'Heading', 'required' => true]
     */
    public static function schema(): array;

    /**
     * Default data when a new block is created.
     */
    public static function defaultData(): array;

    /**
     * Validation rules for block content.
     */
    public static function validationRules(): array;

    /**
     * Blade component name for rendering.
     */
    public static function component(): string;
}
