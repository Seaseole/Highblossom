<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Throwable;

/**
 * Copies the local public media folder into the object-storage bucket.
 *
 * One-off cutover tool: run it from a machine that still has the historical
 * files on disk, against a public disk configured for the s3 driver. Existing
 * keys are skipped so the command is safe to re-run, and --dry-run reports the
 * plan without writing anything.
 */
final class MediaMigrateToS3 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'media:migrate-to-s3
                            {--source= : Absolute path of the local media root to read from}
                            {--prefix= : Only migrate keys under this folder, e.g. gallery}
                            {--dry-run : List what would be uploaded without writing}
                            {--force : Re-upload and overwrite keys that already exist}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Upload the local public media folder to the object-storage bucket';

    /**
     * Key used to prove the bucket accepts writes before the real run starts.
     *
     * @var string
     */
    private const PROBE_KEY = '.media-migration-probe';

    /**
     * Execute the console command.
     *
     * @return int Command exit code
     */
    public function handle(): int
    {
        $source = $this->resolveSource();

        if ($source === null) {
            return self::FAILURE;
        }

        $disk = Storage::disk('public');

        if (config('filesystems.disks.public.driver') !== 's3') {
            $this->error('The public disk is not using the s3 driver. Set PUBLIC_DISK_DRIVER=s3 and the AWS_* secrets first.');

            return self::FAILURE;
        }

        if (! $this->probeTarget($disk)) {
            return self::FAILURE;
        }

        $files = $this->collectFiles($source);

        if ($files === []) {
            $this->warn('No files found under '.$source);

            return self::SUCCESS;
        }

        $this->info(sprintf('Found %d files (%s) under %s', count($files), $this->humanSize(array_sum(array_map(
            fn (\SplFileInfo $file): int => $file->getSize(),
            $files
        ))), $source));

        return (bool) $this->option('dry-run')
            ? $this->reportPlan($files, $disk, $source)
            : $this->upload($files, $disk, $source);
    }

    /**
     * Resolve and validate the local media root to read from.
     *
     * @return string|null Absolute source path, or null when it is unusable
     */
    private function resolveSource(): ?string
    {
        $source = (string) ($this->option('source') ?: storage_path('app/public'));

        if (! File::isDirectory($source)) {
            $this->error("Source directory does not exist: {$source}");

            return null;
        }

        return rtrim($source, DIRECTORY_SEPARATOR);
    }

    /**
     * Write and remove a probe key so a bad credential fails before any real work.
     */
    private function probeTarget(Filesystem $disk): bool
    {
        try {
            $disk->put(self::PROBE_KEY, 'probe');
            $disk->delete(self::PROBE_KEY);

            return true;
        } catch (Throwable $e) {
            $this->error('Bucket is not writable: '.$e->getMessage());

            return false;
        }
    }

    /**
     * List the candidate files, honouring --prefix.
     *
     * @return array<int, \SplFileInfo>
     */
    private function collectFiles(string $source): array
    {
        $prefix = $this->option('prefix') ? trim((string) $this->option('prefix'), '/').'/' : null;

        return array_values(array_filter(
            File::allFiles($source),
            fn (\SplFileInfo $file): bool => $prefix === null
                || str_starts_with($this->keyFor($source, $file), $prefix)
        ));
    }

    /**
     * Build the bucket key for a local file.
     *
     * @param string $source Absolute source root
     *
     * @return string Bucket-relative path with forward slashes
     */
    private function keyFor(string $source, \SplFileInfo $file): string
    {
        return str_replace('\\', '/', substr($file->getRealPath(), strlen($source) + 1));
    }

    /**
     * Report the upload plan without writing anything.
     *
     * @param array<int, \SplFileInfo> $files
     * @param string                   $source Absolute local media root
     */
    private function reportPlan(array $files, Filesystem $disk, string $source): int
    {
        $skipped = 0;

        foreach ($files as $file) {
            $key = $this->keyFor($source, $file);

            if (! (bool) $this->option('force') && $disk->exists($key)) {
                $skipped++;
                $this->line("  exists  {$key}");

                continue;
            }

            $this->line(sprintf('  upload  %s (%s)', $key, $this->humanSize((int) $file->getSize())));
        }

        $this->info(sprintf('[dry run] %d to upload, %d already present', count($files) - $skipped, $skipped));

        return self::SUCCESS;
    }

    /**
     * Stream every candidate file to the bucket.
     *
     * @param array<int, \SplFileInfo> $files
     * @param string                   $source Absolute local media root
     */
    private function upload(array $files, Filesystem $disk, string $source): int
    {
        $uploaded = 0;
        $skipped = 0;
        $failed = 0;
        $bar = $this->output->createProgressBar(count($files));
        $bar->setFormat('%current%/%max% [%bar%] %percent:3s%% %message%');
        $bar->setMessage('starting');

        foreach ($files as $file) {
            $key = $this->keyFor($source, $file);
            $bar->setMessage($key);

            if (! (bool) $this->option('force') && $disk->exists($key)) {
                $skipped++;
                $bar->advance();

                continue;
            }

            $handle = @fopen($file->getRealPath(), 'rb');

            if ($handle === false) {
                $failed++;
                $this->newLine();
                $this->error("Could not read {$key}");
                $bar->advance();

                continue;
            }

            try {
                // Streams rather than buffers, so videos never hit the memory limit.
                $disk->writeStream($key, $handle);
                $uploaded++;
            } catch (Throwable $e) {
                $failed++;
                $this->newLine();
                $this->error("Failed to upload {$key}: ".$e->getMessage());
            } finally {
                if (is_resource($handle)) {
                    fclose($handle);
                }
            }

            $bar->advance();
        }

        $bar->setMessage('done');
        $bar->finish();
        $this->newLine(2);
        $this->info("Uploaded {$uploaded}, skipped {$skipped}, failed {$failed}.");

        return $failed > 0 ? self::FAILURE : self::SUCCESS;
    }

    /**
     * Format a byte count for console output.
     *
     * @param int $bytes Raw byte count
     */
    private function humanSize(int $bytes): string
    {
        return match (true) {
            $bytes >= 1048576 => round($bytes / 1048576, 2).' MB',
            $bytes >= 1024 => round($bytes / 1024, 1).' KB',
            default => $bytes.' B',
        };
    }
}
