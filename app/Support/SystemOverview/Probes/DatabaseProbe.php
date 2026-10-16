<?php

declare(strict_types=1);

namespace App\Support\SystemOverview\Probes;

use App\Support\SystemOverview\ProbeCost;
use App\Support\SystemOverview\ProbeItem;
use App\Support\SystemOverview\ProbeStatus;
use App\Support\SystemOverview\SystemProbe;
use Illuminate\Database\Connection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Number;

/**
 * The default database connection: reachability, shape, and migration drift.
 *
 * Every branch is a single aggregate statement; nothing here walks tables or
 * counts rows, so the section stays cheap on a large production schema.
 */
final class DatabaseProbe implements SystemProbe
{
    public function key(): string
    {
        return 'database';
    }

    public function label(): string
    {
        return 'Database';
    }

    public function description(): string
    {
        return 'Default connection, footprint, and unrun migrations.';
    }

    public function icon(): string
    {
        return 'circle-stack';
    }

    public function cost(): ProbeCost
    {
        return ProbeCost::Light;
    }

    /**
     * @return list<ProbeItem>
     */
    public function run(): array
    {
        $connection = DB::connection();
        $driver = $connection->getDriverName();

        $items = [
            ProbeItem::flagged('Connection', 'Connected', ProbeStatus::Ok),
            ProbeItem::text('Driver', $driver),
            ProbeItem::text('Database', $this->databaseName($connection, $driver)),
            ProbeItem::text('Server version', $this->version($connection, $driver)),
            ProbeItem::text('Footprint', $this->size($connection, $driver)),
            ProbeItem::text('Tables', Number::forHumans($this->tableCount($connection, $driver))),
            $this->migrations($connection),
        ];

        // Always measured, so the cache is the same for every reader; the ability is
        // applied when the section is read.
        $items[] = $this->endpoint()->requires('manage environment')->sensitive();

        return $items;
    }

    /**
     * @param Connection $connection Default connection.
     * @param string     $driver     Driver name.
     */
    private function databaseName(Connection $connection, string $driver): string
    {
        $name = $connection->getDatabaseName();

        if ($driver === 'sqlite' && $name !== '') {
            return basename($name);
        }

        return $name === '' ? '—' : $name;
    }

    /**
     * @param Connection $connection Default connection.
     * @param string     $driver     Driver name.
     */
    private function version(Connection $connection, string $driver): string
    {
        $statement = match ($driver) {
            'pgsql' => 'SELECT version() AS version',
            'sqlite' => 'SELECT sqlite_version() AS version',
            default => 'SELECT VERSION() AS version',
        };

        return (string) $connection->selectOne($statement)->version;
    }

    /**
     * @param Connection $connection Default connection.
     * @param string     $driver     Driver name.
     */
    private function size(Connection $connection, string $driver): string
    {
        return Number::fileSize($this->sizeBytes($connection, $driver), precision: 1);
    }

    /**
     * @param Connection $connection Default connection.
     * @param string     $driver     Driver name.
     */
    private function sizeBytes(Connection $connection, string $driver): int
    {
        if ($driver === 'sqlite') {
            $pageSize = $this->pragma($connection, 'page_size');
            $pageCount = $this->pragma($connection, 'page_count');

            return $pageSize * $pageCount;
        }

        $statement = $driver === 'pgsql'
            ? 'SELECT pg_database_size(current_database()) AS bytes'
            : 'SELECT COALESCE(SUM(data_length + index_length), 0) AS bytes FROM information_schema.tables WHERE table_schema = ?';

        $row = $driver === 'pgsql'
            ? $connection->selectOne($statement)
            : $connection->selectOne($statement, [$connection->getDatabaseName()]);

        return (int) $row->bytes;
    }

    /**
     * @param Connection $connection Default connection.
     * @param string     $pragma     PRAGMA to read.
     */
    private function pragma(Connection $connection, string $pragma): int
    {
        $row = (array) $connection->selectOne('PRAGMA '.$pragma);

        // SQLite names the column after the pragma, so the value is never at index zero.
        $value = $row === [] ? 0 : reset($row);

        return (int) $value;
    }

    /**
     * @param Connection $connection Default connection.
     * @param string     $driver     Driver name.
     */
    private function tableCount(Connection $connection, string $driver): int
    {
        $statement = match ($driver) {
            'sqlite' => "SELECT COUNT(*) AS count FROM sqlite_master WHERE type = 'table' AND name NOT LIKE 'sqlite_%'",
            'pgsql' => "SELECT COUNT(*) AS count FROM information_schema.tables WHERE table_schema = 'public'",
            default => 'SELECT COUNT(*) AS count FROM information_schema.tables WHERE table_schema = ?',
        };

        $row = $driver === 'sqlite' || $driver === 'pgsql'
            ? $connection->selectOne($statement)
            : $connection->selectOne($statement, [$connection->getDatabaseName()]);

        return (int) $row->count;
    }

    /**
     * Compare recorded migrations with the migration files on disk.
     *
     * @param Connection $connection Default connection.
     */
    private function migrations(Connection $connection): ProbeItem
    {
        if (! $connection->getSchemaBuilder()->hasTable('migrations')) {
            return ProbeItem::flagged('Migrations', 'No migrations table', ProbeStatus::Fail);
        }

        $run = $connection->table('migrations')->count();
        $files = count(File::glob(base_path('database/migrations/*.php')));
        $pending = max(0, $files - $run);

        return ProbeItem::flagged(
            'Migrations',
            $run.' run / '.$files.' files',
            $pending > 0 ? ProbeStatus::Warn : ProbeStatus::Ok,
            $pending > 0 ? $pending.' migration(s) have not run.' : null,
        );
    }

    /**
     * Host and port, which the export payload must never carry.
     */
    private function endpoint(): ProbeItem
    {
        $config = (array) config('database.connections.'.config('database.default'));

        $host = (string) ($config['host'] ?? 'localhost');
        $port = (string) ($config['port'] ?? '');

        return ProbeItem::text('Endpoint', $port === '' ? $host : $host.':'.$port);
    }
}
