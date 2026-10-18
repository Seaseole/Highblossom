<?php

declare(strict_types=1);

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Database\Eloquent\Model;

/**
 * Reject a sort order that another record in the same scope already holds.
 *
 * The database unique index is the real guard; this rule exists to turn that
 * constraint into an inline message naming the record in the way.
 */
final class SortOrderIsUnique implements ValidationRule
{
    /**
     * @param class-string         $modelClass
     * @param array<string, mixed> $scopeValues
     */
    public function __construct(
        private string $modelClass,
        private array $scopeValues = [],
        private ?int $ignoreId = null,
    ) {}

    /**
     * Build the rule for a model, reading its scope columns from the submitted data
     * and falling back to the stored row for scopes the form cannot change.
     *
     * @param class-string         $modelClass
     * @param array<string, mixed> $input
     * @param mixed                $current    the bound model being edited, or its primary key
     */
    public static function for(string $modelClass, array $input = [], mixed $current = null): self
    {
        /** @var Model $instance */
        $instance = new $modelClass;
        $model = $current instanceof Model ? $current : null;
        $ignoreId = $model?->getKey() ?? (is_int($current) || is_string($current) ? (int) $current : null);
        $scopeValues = [];

        foreach ($instance->sortOrderScopeColumns() as $column) {
            $scopeValues[$column] = $input[$column] ?? $model?->getAttribute($column);
        }

        return new self($modelClass, $scopeValues, $ignoreId === null ? null : (int) $ignoreId);
    }

    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($value === null || $value === '') {
            return;
        }

        /** @var class-string $modelClass */
        $modelClass = $this->modelClass;
        $clash = $modelClass::findBySortOrder((int) $value, $this->scopeValues, $this->ignoreId);

        if ($clash === null) {
            return;
        }

        $fail(self::messageFor($clash, (int) $value));
    }

    /**
     * The inline message naming the record that already holds the number.
     */
    public static function messageFor(Model $clash, int $sortOrder): string
    {
        $label = $clash->getAttribute($clash->sortOrderLabelColumn());

        return sprintf(
            'Sort order %d is already used by %s.',
            $sortOrder,
            $label === null ? "record #{$clash->getKey()}" : "'{$label}'",
        );
    }
}
