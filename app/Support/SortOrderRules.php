<?php

declare(strict_types=1);

namespace App\Support;

use App\Rules\SortOrderIsUnique;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

/**
 * One place that decides how a sort-order field is validated.
 *
 * A blank field means "give me the next number" on create and "keep the number I
 * already have" on edit, so a status-only save can never null a rank out.
 */
final class SortOrderRules
{
    /**
     * The rules for the model's ordering column.
     *
     * @param class-string         $modelClass
     * @param array<string, mixed> $input
     * @param mixed                $current    the bound model being edited, if any
     */
    public static function rules(string $modelClass, array $input, mixed $current = null): array
    {
        return [
            'nullable',
            'integer',
            'min:1',
            SortOrderIsUnique::for($modelClass, $input, $current),
        ];
    }

    /**
     * Turn "no number given" into a single answer the rest of the pipeline can use.
     *
     * A blank field - and the 0 that older forms and the modal sent as "unset" - means
     * the next free number on create, and the number the record already has on edit.
     * Anything else is left for the rules above to validate, so a negative stays an
     * error rather than silently becoming a rank.
     *
     * @param class-string $modelClass
     * @param mixed        $current    the bound model being edited, if any
     */
    public static function normalize(Request $request, string $modelClass, mixed $current = null): void
    {
        $instance = new $modelClass;
        $column = $instance->sortOrderColumn();
        $value = $request->input($column);

        $stored = $current instanceof Model ? $current->getAttribute($column) : null;

        if ($value === null || $value === '' || $value === 0 || $value === '0') {
            $request->merge([$column => $stored]);
        }
    }
}
