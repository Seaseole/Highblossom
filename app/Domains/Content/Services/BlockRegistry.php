<?php

declare(strict_types=1);

namespace App\Domains\Content\Services;

use App\Domains\Content\Contracts\BlockTypeInterface;
use Illuminate\Cache\Repository as CacheRepository;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use ReflectionClass;
use Symfony\Component\Finder\Finder;

final class BlockRegistry
{
    private $blocks = null;

    public function __construct(
        private readonly CacheRepository $cache,
        private readonly Filesystem $filesystem
    ) {}

    /**
     * Get all registered block types.
     *
     * @return Collection<string>
     */
    public function all(): Collection
    {
        if ($this->blocks === null) {
            $cached = $this->cache->rememberForever('content.block_types', function () {
                return $this->discoverBlocks()->toArray();
            });

            $this->blocks = collect($cached);
        }

        return $this->blocks;
    }

    /**
     * Get blocks grouped by category.
     */
    public function groupedByCategory(): Collection
    {
        return $this->all()->groupBy(fn (string $className) => $className::category());
    }

    /**
     * Find a block type by its ID.
     */
    public function find(string $id): ?string
    {
        return $this->all()->first(fn (string $className) => $className::id() === $id);
    }

    /**
     * Check if a block type exists.
     */
    public function has(string $id): bool
    {
        return $this->find($id) !== null;
    }

    /**
     * Get block categories with their blocks.
     */
    public function categories(): array
    {
        return [
            'layout' => ['name' => 'Layout', 'icon' => 'layout-grid'],
            'content' => ['name' => 'Content', 'icon' => 'type'],
            'media' => ['name' => 'Media', 'icon' => 'image'],
            'blog' => ['name' => 'Blog', 'icon' => 'file-text'],
        ];
    }

    /**
     * Clear the cached block types.
     */
    public function clearCache(): void
    {
        $this->cache->forget('content.block_types');
        $this->blocks = null;
    }

    /**
     * Discover block types in the Blocks directory.
     *
     * @return Collection<BlockTypeInterface>
     */
    private function discoverBlocks(): Collection
    {
        $blocksPath = app_path('Domains/Content/Blocks');

        if (! $this->filesystem->isDirectory($blocksPath)) {
            return collect();
        }

        $blocks = collect();
        $finder = new Finder();
        $finder->files()->in($blocksPath)->name('*Block.php');

        foreach ($finder as $file) {
            $className = $this->getClassFromFile($file->getRealPath());

            if ($className === null) {
                continue;
            }

            if (! class_exists($className)) {
                continue;
            }

            $reflection = new ReflectionClass($className);

            if (! $reflection->implementsInterface(BlockTypeInterface::class)) {
                continue;
            }

            if ($reflection->isAbstract()) {
                continue;
            }

            $blocks->push($className);
        }

        return $blocks->sortBy(fn (string $class) => $class::name());
    }

    /**
     * Extract the fully-qualified class name from a file.
     */
    private function getClassFromFile(string $path): ?string
    {
        $contents = $this->filesystem->get($path);

        // Extract namespace
        $namespace = null;
        if (preg_match('/namespace\s+([^;]+);/', $contents, $matches)) {
            $namespace = $matches[1];
        }

        // Extract class name
        $class = null;
        if (preg_match('/class\s+(\w+)/', $contents, $matches)) {
            $class = $matches[1];
        }

        if ($namespace && $class) {
            return $namespace . '\\' . $class;
        }

        return null;
    }
}
