<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Support\SortOrderRenumberPlan;
use Illuminate\Console\Command;

use function Laravel\Prompts\table;

/**
 * Reports what the sort-order renumber migration would change, without writing.
 *
 * Run it against production first: the numbers it prints are the same ones the
 * migration will act on, because both read SortOrderRenumberPlan.
 */
final class PreviewSortOrderRenumber extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sort-orders:preview';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Preview the admin sort-order renumbering without changing any row';

    /**
     * Print the plan, table by scope.
     */
    public function handle(): int
    {
        $groups = SortOrderRenumberPlan::build();

        if ($groups === []) {
            $this->components->warn('No ordered rows found; nothing to renumber.');

            return self::SUCCESS;
        }

        $rows = [];
        $total = 0;

        foreach ($groups as $group) {
            $changes = array_values(array_filter($group['rows'], fn (array $row) => $row['old'] !== $row['new']));
            $total += count($changes);

            $samples = array_map(
                fn (array $row) => "{$row['id']}: {$row['old']} -> {$row['new']}",
                array_slice($changes, 0, 5)
            );

            if (count($changes) > 5) {
                $samples[] = '+'.(count($changes) - 5).' more';
            }

            $rows[] = [
                $group['table'].'.'.$group['column'],
                $group['scope'],
                count($group['rows']),
                count($changes),
                $samples === [] ? '-' : implode(', ', $samples),
            ];
        }

        table(
            ['Column', 'Scope', 'Rows', 'Changing', 'Sample (id: old -> new)'],
            $rows,
        );

        $this->newLine();

        if ($total === 0) {
            $this->components->info('Already dense and unique: the renumber would change nothing.');
        } else {
            $this->components->warn("The migration would renumber {$total} row(s). Run sort-orders:preview again after migrating.");
        }

        return self::SUCCESS;
    }
}
