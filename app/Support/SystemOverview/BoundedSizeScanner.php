<?php

declare(strict_types=1);

namespace App\Support\SystemOverview;

use FilesystemIterator;
use RecursiveCallbackFilterIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;
use Throwable;

/**
 * Measures a directory tree under a hard file count and wall-clock budget.
 *
 * Only heavy probes use it, and those are warmed off the request path. The caps
 * exist so one unexpected tree - a stray node_modules, a symlink loop, a mounted
 * backup drive - cannot turn into an unbounded walk. Links are never followed or
 * counted, which is what keeps public/storage from being measured twice through
 * its own symlink.
 */
final class BoundedSizeScanner
{
    /**
     * Directory names skipped at any depth.
     *
     * @var list<string>
     */
    private const PRUNED = ['vendor', 'node_modules', '.git', '.idea', '.vscode', 'build'];

    /**
     * @param int $maxFiles Hard ceiling on counted files.
     * @param int $budgetMs Wall-clock budget in milliseconds.
     */
    public function __construct(
        private readonly int $maxFiles = 30000,
        private readonly int $budgetMs = 500,
    ) {}

    /**
     * Walk one directory, stopping at whichever cap it hits first.
     *
     * @param string $path Absolute directory path.
     *
     * @return array{files: int, bytes: int, truncated: bool}
     */
    public function scan(string $path): array
    {
        if (is_link($path) || ! is_dir($path)) {
            return ['files' => 0, 'bytes' => 0, 'truncated' => false];
        }

        $startedAt = microtime(true);
        $files = 0;
        $bytes = 0;
        $truncated = false;

        try {
            foreach ($this->walk($path) as $entry) {
                if ($files >= $this->maxFiles || $this->elapsedMs($startedAt) > $this->budgetMs) {
                    $truncated = true;

                    break;
                }

                // LEAVES_ONLY yields empty directories as iterators, not files.
                if (! $entry instanceof SplFileInfo || ! $entry->isFile()) {
                    continue;
                }

                $files++;
                $bytes += $entry->isReadable() ? max(0, $entry->getSize()) : 0;
            }
        } catch (Throwable) {
            // An unreadable subtree yields a partial result, not a failed section.
            $truncated = true;
        }

        return ['files' => $files, 'bytes' => $bytes, 'truncated' => $truncated];
    }

    /**
     * A leaves-only iterator that refuses to descend into pruned or linked directories.
     *
     * @param string $path Absolute directory path.
     *
     * @return RecursiveIteratorIterator<SplFileInfo>
     */
    private function walk(string $path): RecursiveIteratorIterator
    {
        $root = new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS);

        $filtered = new RecursiveCallbackFilterIterator(
            $root,
            static fn (SplFileInfo $entry): bool => $entry->isDir()
                ? ! $entry->isLink() && ! in_array($entry->getFilename(), self::PRUNED, true)
                : ! $entry->isLink(),
        );

        $iterator = new RecursiveIteratorIterator($filtered, RecursiveIteratorIterator::LEAVES_ONLY);

        return $iterator;
    }

    /**
     * @param float $startedAt Result of microtime(true) at the start.
     */
    private function elapsedMs(float $startedAt): int
    {
        return (int) round((microtime(true) - $startedAt) * 1000);
    }
}
