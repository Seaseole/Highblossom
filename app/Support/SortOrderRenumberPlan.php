<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Works out how every admin ordering column should be renumbered.
 *
 * One implementation serves both the migration that writes the change and the
 * preview command that only reports it, so the two can never disagree about what
 * "keep the current order, close the gaps" means.
 */
final class SortOrderRenumberPlan
{
    /**
     * Table, column and scope columns for each ordered surface.
     *
     * @var array<int, array{table: string, column: string, scopes: array<int, string>}>
     */
    public const TARGETS = [
        ['table' => 'services', 'column' => 'sort_order', 'scopes' => []],
        ['table' => 'testimonials', 'column' => 'sort_order', 'scopes' => []],
        ['table' => 'gallery_images', 'column' => 'sort_order', 'scopes' => ['source']],
        ['table' => 'gallery_categories', 'column' => 'sort_order', 'scopes' => []],
        ['table' => 'glass_types', 'column' => 'sort_order', 'scopes' => []],
        ['table' => 'glass_sub_categories', 'column' => 'sort_order', 'scopes' => ['glass_type_id']],
        ['table' => 'service_types', 'column' => 'sort_order', 'scopes' => []],
        ['table' => 'partners', 'column' => 'order', 'scopes' => []],
        ['table' => 'staff', 'column' => 'order', 'scopes' => []],
    ];

    /**
     * Every scope group, with the rows in their current order and the number each
     * one should take.
     *
     * @return array<int, array{table: string, column: string, scope: string, rows: array<int, array{id: int, old: int|null, new: int}>}>
     */
    public static function build(): array
    {
        $groups = [];

        foreach (self::TARGETS as $target) {
            if (! Schema::hasTable($target['table'])) {
                continue;
            }

            $rows = DB::table($target['table'])
                ->select(array_merge(['id', $target['column']], $target['scopes']))
                ->get();

            if ($rows->isEmpty()) {
                continue;
            }

            $byScope = $rows->groupBy(fn ($row) => implode(' = ', array_map(
                fn (string $scope) => $scope.': '.($row->{$scope} ?? 'NULL'),
                $target['scopes']
            )));

            foreach ($byScope as $scope => $group) {
                $ordered = $group->sortBy([
                    fn ($a, $b) => self::key($a, $target['column']) <=> self::key($b, $target['column']),
                ])->values();

                $rowsOut = [];

                foreach ($ordered as $index => $row) {
                    $rowsOut[] = [
                        'id' => (int) $row->id,
                        'old' => $row->{$target['column']} === null ? null : (int) $row->{$target['column']},
                        'new' => $index + 1,
                    ];
                }

                $groups[] = [
                    'table' => $target['table'],
                    'column' => $target['column'],
                    'scope' => $target['scopes'] === [] ? 'global' : (string) $scope,
                    'rows' => $rowsOut,
                ];
            }
        }

        return $groups;
    }

    /**
     * Rows sort by their current number, nulls and zeros first, then by id so the
     * result is the same on every run and every engine.
     *
     * @return array{0: int, 1: int}
     */
    private static function key(object $row, string $column): array
    {
        return [(int) $row->{$column}, (int) $row->id];
    }
}
