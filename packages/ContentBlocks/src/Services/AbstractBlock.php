<?php

namespace Highblossom\ContentBlocks\Services;

use Highblossom\ContentBlocks\Contracts\BlockInterface;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Illuminate\Support\Str;

abstract class AbstractBlock implements BlockInterface
{
    protected string $viewNamespace = 'content-blocks';

    /**
     * Render the block with the given attributes.
     *
     * @param array $attributes
     * @return string
     */
    public function render(array $attributes = []): string
    {
        $attributes = $this->normalizeAttributes($attributes);

        if (!$this->validate($attributes)) {
            return $this->renderError($attributes);
        }

        try {
            return $this->buildView($attributes);
        } catch (\Throwable $e) {
            Log::error("ContentBlocks view rendering failed for {$this->getType()} block", [
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'attributes' => $attributes,
            ]);

            return $this->renderError($attributes);
        }
    }

    /**
     * Validate the block attributes.
     *
     * @param array $attributes
     * @return bool
     */
    public function validate(array $attributes): bool
    {
        $rules = $this->getValidationRules();
        $validator = Validator::make($attributes, $rules);

        if ($validator->fails()) {
            Log::warning("ContentBlocks validation failed for {$this->getType()} block", [
                'errors' => $validator->errors()->toArray(),
                'attributes' => $attributes,
            ]);

            return false;
        }

        return true;
    }

    /**
     * Build and return the view for this block.
     *
     * @param array $attributes
     * @return string
     */
    protected function buildView(array $attributes): string
    {
        $viewName = $this->getViewName();
        $fullViewName = "{$this->viewNamespace}::{$viewName}";

        return view($fullViewName, $this->getViewData($attributes))->render();
    }

    /**
     * Get the view name for this block.
     *
     * @return string
     */
    protected function getViewName(): string
    {
        return Str::snake(str_replace('Block', '', class_basename($this)));
    }

    /**
     * Get the data to pass to the view.
     *
     * @param array $attributes
     * @return array
     */
    protected function getViewData(array $attributes): array
    {
        return $attributes;
    }

    /**
     * Normalize attributes by merging with defaults and type casting.
     *
     * @param array $attributes
     * @return array
     */
    protected function normalizeAttributes(array $attributes): array
    {
        $defaults = $this->getDefaultAttributes();
        $merged = array_merge($defaults, $attributes);

        return $this->castAttributes($merged);
    }

    /**
     * Cast attributes to their proper types.
     *
     * @param array $attributes
     * @return array
     */
    protected function castAttributes(array $attributes): array
    {
        $casts = $this->getAttributeCasts();

        foreach ($casts as $key => $type) {
            if (isset($attributes[$key])) {
                $attributes[$key] = $this->castAttribute($type, $attributes[$key]);
            }
        }

        return $attributes;
    }

    /**
     * Cast a single attribute to its type.
     *
     * @param string $type
     * @param mixed $value
     * @return mixed
     */
    protected function castAttribute(string $type, mixed $value): mixed
    {
        return match ($type) {
            'int', 'integer' => (int) $value,
            'float', 'double' => (float) $value,
            'bool', 'boolean' => filter_var($value, FILTER_VALIDATE_BOOLEAN),
            'string' => (string) $value,
            'array' => is_array($value) ? $value : json_decode($value, true) ?? [],
            default => $value,
        };
    }

    /**
     * Get the attribute type casts.
     *
     * @return array
     */
    protected function getAttributeCasts(): array
    {
        return [];
    }

    /**
     * Render an error state for invalid attributes.
     *
     * @param array $attributes
     * @return string
     */
    protected function renderError(array $attributes): string
    {
        $rules = $this->getValidationRules();
        $validator = Validator::make($attributes, $rules);

        Log::warning("ContentBlocks rendering failed for {$this->getType()} block - invalid attributes", [
            'errors' => $validator->fails() ? $validator->errors()->toArray() : [],
            'attributes' => $attributes,
        ]);

        $debugMode = config('content-blocks.debug_mode', false);

        if ($debugMode) {
            return "<!-- Invalid attributes for {$this->getType()} block -->";
        }

        return '';
    }
}
