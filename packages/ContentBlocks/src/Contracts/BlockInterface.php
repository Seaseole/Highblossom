<?php

namespace Highblossom\ContentBlocks\Contracts;

interface BlockInterface
{
    /**
     * Render the block with the given attributes.
     */
    public function render(array $attributes = []): string;

    /**
     * Get the block type identifier.
     */
    public function getType(): string;

    /**
     * Validate the block attributes.
     */
    public function validate(array $attributes): bool;

    /**
     * Get the validation rules for this block.
     */
    public function getValidationRules(): array;

    /**
     * Get the default attributes for this block.
     */
    public function getDefaultAttributes(): array;
}
