<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Facades\DB;

/**
 * Automatic, unique, status-independent ordering for a model.
 *
 * The reserved number is `MAX(column) + 1` within the model's declared scope
 * columns, read under `lockForUpdate()` inside a transaction and retried if the
 * database still manages to reject the insert, so two admins creating records at
 * the same moment cannot both be handed the same number.
 */
trait HasSortOrder
{
    /**
     * How many times a clashing auto-assigned insert is retried before giving up.
     */
    protected int $sortOrderRetries = 3;

    /**
     * The ordering column name. Override on models whose column is not sort_order.
     */
    public function sortOrderColumn(): string
    {
        return 'sort_order';
    }

    /**
     * The columns that partition the numbering space. Override to scope numbering.
     *
     * @return array<int, string>
     */
    public function sortOrderScopeColumns(): array
    {
        return [];
    }

    /**
     * The column quoted in a clash message. Override when the label is not `name`.
     */
    public function sortOrderLabelColumn(): string
    {
        return 'name';
    }

    /**
     * The number the next record in this scope would receive.
     *
     * Read-only sibling of the reservation used during insert; safe to call from a
     * controller to pre-fill a create form.
     *
     * @param array<string, mixed> $scopeValues
     */
    public static function nextSortOrder(array $scopeValues = []): int
    {
        return self::maxSortOrder($scopeValues) + 1;
    }

    /**
     * Highest number in the scope, counting inactive and soft-deleted rows so a
     * number is never handed out twice.
     *
     * @param array<string, mixed> $scopeValues
     */
    public static function maxSortOrder(array $scopeValues = [], bool $forUpdate = false): int
    {
        $model = new static;
        $query = static::query();

        if (self::sortOrderTracksSoftDeletes()) {
            $query->withTrashed();
        }

        foreach ($model->sortOrderScopeColumns() as $column) {
            $value = $scopeValues[$column] ?? null;
            $value === null ? $query->whereNull($column) : $query->where($column, $value);
        }

        if ($forUpdate) {
            $query->lockForUpdate();
        }

        return (int) $query->max($model->sortOrderColumn());
    }

    /**
     * The record already holding this number in the scope, if any.
     *
     * @param array<string, mixed> $scopeValues
     */
    public static function findBySortOrder(int $sortOrder, array $scopeValues = [], ?int $ignoreId = null): ?Model
    {
        $model = new static;
        $query = static::query();

        if (self::sortOrderTracksSoftDeletes()) {
            $query->withTrashed();
        }

        foreach ($model->sortOrderScopeColumns() as $column) {
            $value = $scopeValues[$column] ?? null;
            $value === null ? $query->whereNull($column) : $query->where($column, $value);
        }

        if ($ignoreId !== null) {
            $query->whereKeyNot($ignoreId);
        }

        return $query->where($model->sortOrderColumn(), $sortOrder)->first();
    }

    /**
     * Insert the row, reserving an unused number first when the caller left it empty.
     *
     * Overrides Eloquent's insert so the reservation and the write share one
     * transaction; an explicitly supplied number is honoured untouched and simply
     * meets the unique index.
     *
     * @param Builder<static> $query
     */
    protected function performInsert(Builder $query): bool
    {
        if (! empty($this->getAttribute($this->sortOrderColumn()))) {
            return parent::performInsert($query);
        }

        return $this->insertWithReservedSortOrder($query);
    }

    /**
     * Reserve a number under a row lock and insert, retrying a lost race.
     *
     * @param Builder<static> $query
     */
    private function insertWithReservedSortOrder(Builder $query): bool
    {
        for ($attempt = 1; ; $attempt++) {
            try {
                return DB::transaction(function () use ($query): bool {
                    $this->setAttribute(
                        $this->sortOrderColumn(),
                        static::maxSortOrder($this->currentSortOrderScopeValues(), true) + 1
                    );

                    return parent::performInsert($query);
                });
            } catch (UniqueConstraintViolationException $exception) {
                if ($attempt >= $this->sortOrderRetries) {
                    throw $exception;
                }
            }
        }
    }

    /**
     * The scope values carried by this instance.
     *
     * @return array<string, mixed>
     */
    public function currentSortOrderScopeValues(): array
    {
        $values = [];

        foreach ($this->sortOrderScopeColumns() as $column) {
            $values[$column] = $this->getAttribute($column);
        }

        return $values;
    }

    /**
     * Whether soft-deleted rows must stay inside the numbering space.
     */
    protected static function sortOrderTracksSoftDeletes(): bool
    {
        return in_array(SoftDeletes::class, class_uses_recursive(static::class), true);
    }
}
