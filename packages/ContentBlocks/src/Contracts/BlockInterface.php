<?php

namespace Highblossom\ContentBlocks\Contracts;

interface BlockInterface
{
    /**
     * Render the block with the given attributes.
     *
     * @param array $attributes
     * @return string
     */
    public function render(array $attributes = []): string;

    /**
     * Get the block type identifier.
     *
     * @return string
     */
    public function getType(): string;

    /**
     * Validate the block attributes.
     *
     * @param array $attributes
     * @return bool
     */
    public function validate(array $attributes): bool;

    /**
     * Get the validation rules for this block.
     *
     * @return array
     */
    public function getValidationRules(): array;

    /**
     * Get the default attributes for this block.
     *
     * @return array
     */
    public function getDefaultAttributes(): array;
}
