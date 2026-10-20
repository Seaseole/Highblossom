<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\User;
use App\Support\Username;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;

/**
 * Give every existing account a username derived from its email address.
 *
 * Run with --preview first: it prints the whole table and writes nothing, so the names can
 * be read before they become the thing people sign in with. A backfill that guesses is
 * worse than no backfill at all, because a person who later finds their account is called
 * "j-doe-2" has no way to tell how that happened or how to undo it.
 *
 * Kept out of the migration on purpose. This repository's last production migration failed
 * because a data step was baked into a schema change, and an already-deployed column is
 * something the application can run without.
 */
class BackfillUsernames extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'users:backfill-usernames
                            {--preview : Show the names that would be assigned and write nothing}';

    /**
     * The console command description.
     */
    protected $description = 'Assign a username to every account that does not have one yet';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        if (! Schema::hasColumn('users', 'username')) {
            $this->error('The users.username column does not exist. Run the migration first.');

            return self::FAILURE;
        }

        $preview = (bool) $this->option('preview');
        $rows = $this->plan();

        if ($rows === []) {
            $this->info('Every account already has a username. Nothing to do.');

            return self::SUCCESS;
        }

        $this->table(
            ['id', 'email', 'username', 'status'],
            $rows
        );

        $failed = array_filter($rows, fn (array $row): bool => $row['status'] !== 'ok');

        if ($preview) {
            $this->warn('Preview only — nothing was written. Re-run without --preview to apply.');
        } else {
            foreach ($rows as $row) {
                if ($row['status'] === 'ok') {
                    User::query()->whereKey($row['id'])->update(['username' => $row['username']]);
                }
            }

            $this->info(sprintf(
                'Assigned %1$d username(s). %2$d account(s) could not be given one.',
                count($rows) - count($failed),
                count($failed)
            ));
        }

        return $failed === [] ? self::SUCCESS : self::FAILURE;
    }

    /**
     * Work out every name, in id order, without touching the database.
     *
     * Id order is what makes the result repeatable: run it twice against the same rows and
     * the same collisions resolve the same way, which is the only reason the preview can be
     * trusted to describe the real run.
     *
     * @return list<array{id: int, email: string, username: string, status: string}>
     */
    private function plan(): array
    {
        $taken = User::query()
            ->whereNotNull('username')
            ->pluck('username')
            ->map(fn (string $username): string => Username::normalize($username))
            ->flip();

        $rows = [];

        foreach (User::query()->whereNull('username')->orderBy('id')->get() as $user) {
            $candidate = Username::generateFromEmail(
                (string) $user->email,
                (int) $user->id,
                fn (string $name): bool => isset($taken[$name])
            );

            if ($candidate === null) {
                $rows[] = ['id' => $user->id, 'email' => $user->email, 'username' => '-', 'status' => 'no name available'];

                continue;
            }

            $taken[$candidate] = true;
            $rows[] = ['id' => $user->id, 'email' => $user->email, 'username' => $candidate, 'status' => 'ok'];
        }

        return $rows;
    }
}
