<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Finder\Finder;

final class CleanupTempUploadsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'content-blocks:cleanup-temp-uploads
                            {--hours=24 : The age in hours after which temp files should be deleted}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up temporary uploaded files older than the specified hours (default: 24)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $hours = (int) $this->option('hours');
        $cutoffTime = now()->subHours($hours);

        $tempDisk = Storage::disk('temp');
        $tempPath = $tempDisk->path('');

        if (!is_dir($tempPath)) {
            $this->info("Temp directory does not exist: {$tempPath}");

            return self::SUCCESS;
        }

        $finder = new Finder();
        $finder->files()
            ->in($tempPath)
            ->date("< {$cutoffTime->format('Y-m-d H:i:s')}");

        $count = 0;
        $errors = 0;

        foreach ($finder as $file) {
            $relativePath = $file->getRelativePathname();

            try {
                if ($tempDisk->delete($relativePath)) {
                    $count++;
                    Log::info("Cleaned up old temp file: {$relativePath}");
                } else {
                    $errors++;
                    Log::warning("Failed to delete temp file: {$relativePath}");
                }
            } catch (\Exception $e) {
                $errors++;
                Log::error("Error deleting temp file: {$relativePath}", [
                    'error' => $e->getMessage(),
                ]);
            }
        }

        if ($count > 0) {
            $this->info("Cleaned up {$count} temp file(s) older than {$hours} hour(s).");
        } else {
            $this->info("No temp files found older than {$hours} hour(s).");
        }

        if ($errors > 0) {
            $this->warn("Failed to delete {$errors} file(s).");
        }

        return self::SUCCESS;
    }
}
