<?php

declare(strict_types=1);

use App\Support\SortOrderRenumberPlan;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Renumber every admin ordering column to a dense 1..N line inside its scope.
 *
 * Existing relative order is kept: rows are sorted by their current number, then by
 * id, so duplicates, nulls, zeros and gaps collapse into consecutive ranks without
 * changing which record appears first. The values being replaced are snapshotted
 * first, so a rollback puts every row back exactly where it was.
 *
 * Preview it before running: `php artisan sort-orders:preview`
 *
 * Run this before the unique-index migration; the renumber relies on there being no
 * unique constraint yet.
 */
return new class extends Migration
{
    /**
     * Previous values, so down() can restore them.
     */
    private const SNAPSHOT_TABLE = 'sort_order_renumber_snapshots';

    /**
     * Renumber each scope, recording what was changed.
     */
    public function up(): void
    {
        Schema::create(self::SNAPSHOT_TABLE, function (Blueprint $table) {
            $table->id();
            $table->string('table_name');
            $table->string('column_name');
            $table->unsignedBigInteger('row_id');
            $table->integer('old_value')->nullable();
        });

        DB::transaction(function (): void {
            foreach (SortOrderRenumberPlan::build() as $group) {
                $snapshots = [];

                foreach ($group['rows'] as $row) {
                    $snapshots[] = [
                        'table_name' => $group['table'],
                        'column_name' => $group['column'],
                        'row_id' => $row['id'],
                        'old_value' => $row['old'],
                    ];

                    if ($row['old'] === $row['new']) {
                        continue;
                    }

                    // Written through the query builder on purpose: this is a data
                    // repair, not an edit, and it must not fire observers per row.
                    DB::table($group['table'])
                        ->where('id', $row['id'])
                        ->update([$group['column'] => $row['new']]);
                }

                collect($snapshots)->chunk(500)->each(
                    fn (array $chunk) => DB::table(self::SNAPSHOT_TABLE)->insert($chunk)
                );
            }
        });
    }

    /**
     * Put every row back on the number it held before this migration ran.
     */
    public function down(): void
    {
        if (! Schema::hasTable(self::SNAPSHOT_TABLE)) {
            return;
        }

        DB::transaction(function (): void {
            $byTable = DB::table(self::SNAPSHOT_TABLE)->get()->groupBy('table_name');

            foreach ($byTable as $tableName => $entries) {
                if (! Schema::hasTable((string) $tableName)) {
                    continue;
                }

                foreach ($entries as $entry) {
                    DB::table($tableName)
                        ->where('id', $entry->row_id)
                        ->update([$entry->column_name => $entry->old_value]);
                }
            }
        });

        Schema::dropIfExists(self::SNAPSHOT_TABLE);
    }
};
