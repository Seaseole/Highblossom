<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Services\SessionManager;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Description('Delete expired sessions, close the sign-ins that lapsed, and trim history past the retention window')]
#[Signature('sessions:prune
        {--days= : Override how many days of closed sessions to keep}
        {--dry-run : Report what would be closed and deleted without changing anything}')]
/**
 * Reconciles both session stores with reality on a schedule.
 *
 * The framework session driver expires rows lazily and only on a lottery, and
 * never reports the ones it dropped, so expired rows and the ledger entries that
 * describe them are cleaned up here instead.
 */
final class PruneUserSessionsCommand extends Command
{
    /**
     * Create a new command instance.
     */
    public function __construct(
        protected SessionManager $sessions
    ) {
        parent::__construct();
    }

    /**
     * Run the reconciliation and report what changed.
     *
     * @return int Command exit code
     */
    public function handle(): int
    {
        $retentionDays = $this->option('days') === null ? null : (int) $this->option('days');

        if ($retentionDays !== null && $retentionDays < 1) {
            $this->error('The --days option must be a positive number of days.');

            return self::FAILURE;
        }

        $result = $this->sessions->prune($retentionDays, (bool) $this->option('dry-run'));

        if ($result['dry_run']) {
            $this->warn('Dry run — nothing was changed.');
        }

        $this->info("Closed {$result['expired']} lapsed session(s).");
        $this->info("Closed {$result['orphaned']} orphaned sign-in(s).");
        $this->info("Closed {$result['idle']} idle session(s).");
        $this->info("Deleted {$result['deleted']} expired framework session row(s).");
        $this->info("Removed {$result['purged']} session row(s) past the retention window.");

        return self::SUCCESS;
    }
}
