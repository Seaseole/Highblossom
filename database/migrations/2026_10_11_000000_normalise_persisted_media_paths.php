<?php

declare(strict_types=1);

use Highblossom\ContentBlocks\Support\MediaUrl;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Normalise persisted media references to disk-relative paths.
 *
 * Before object storage, uploads were written into the database as absolute
 * URLs such as https://example.test/storage/gallery/a.jpg. Those URLs are bound
 * to the host that wrote them, so every row goes stale on a domain change or a
 * move to a bucket. MediaUrl resolves relative paths against whichever driver
 * backs the public disk, so rewriting them once makes historical rows portable.
 *
 * Only structured media attributes are rewritten. Rich-text HTML keeps its
 * absolute URLs on purpose: a relative path inside stored HTML would resolve
 * against the page, not the media disk.
 */
return new class extends Migration
{
    /**
     * Block attribute keys that hold a media reference.
     *
     * @var array<int, string>
     */
    private const MEDIA_KEYS = ['src', 'poster'];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $this->normaliseSimpleColumns();
        $this->normalisePostContentBlocks();
    }

    /**
     * Reverse the migrations.
     *
     * Intentionally a no-op. The original absolute URLs are not retained, and
     * rebuilding them from the current disk would not reproduce the stored host.
     */
    public function down(): void
    {
        //
    }

    /**
     * Rewrite the columns that store a single media reference.
     */
    private function normaliseSimpleColumns(): void
    {
        $targets = [
            ['services', 'image_url'],
            ['posts', 'featured_image_url'],
            ['about_us_contents', 'hero_image'],
        ];

        foreach ($targets as [$table, $column]) {
            if (! $this->tableHasColumn($table, $column)) {
                continue;
            }

            DB::table($table)
                ->whereNotNull($column)
                ->where($column, 'like', '%/storage/%')
                ->chunkById(200, function (Collection $rows) use ($table, $column): void {
                    foreach ($rows as $row) {
                        $path = MediaUrl::path((string) $row->{$column});

                        if ($path === null) {
                            continue;
                        }

                        DB::table($table)->where('id', $row->id)->update([$column => $path]);
                    }
                });
        }
    }

    /**
     * Rewrite media references stored inside post content blocks.
     */
    private function normalisePostContentBlocks(): void
    {
        if (! $this->tableHasColumn('posts', 'content')) {
            return;
        }

        DB::table('posts')
            ->whereNotNull('content')
            ->where('content', 'like', '%/storage/%')
            ->chunkById(100, function (Collection $rows): void {
                foreach ($rows as $row) {
                    $blocks = json_decode((string) $row->content, true);

                    if (! is_array($blocks)) {
                        continue;
                    }

                    $updated = $this->normaliseBlocks($blocks);
                    $payload = json_encode($updated, JSON_UNESCAPED_SLASHES);

                    if ($payload !== $row->content) {
                        DB::table('posts')->where('id', $row->id)->update(['content' => $payload]);
                    }
                }
            });
    }

    /**
     * Recursively rewrite media attributes in a block tree.
     *
     * @param array $blocks Decoded content block tree
     *
     * @return array Block tree with disk-relative media paths
     */
    private function normaliseBlocks(array $blocks): array
    {
        foreach ($blocks as &$block) {
            if (! is_array($block) || ! isset($block['type'])) {
                continue;
            }

            $attributes = $block['attributes'] ?? [];

            $attributes = $this->normaliseAttributes($attributes);

            if ($block['type'] === 'columns' && isset($attributes['columns']) && is_array($attributes['columns'])) {
                foreach ($attributes['columns'] as &$column) {
                    if (is_array($column) && isset($column['blocks']) && is_array($column['blocks'])) {
                        $column['blocks'] = $this->normaliseBlocks($column['blocks']);
                    }
                }

                unset($column);
            }

            $block['attributes'] = $attributes;
        }

        unset($block);

        return $blocks;
    }

    /**
     * Rewrite the media references carried by one block's attributes.
     *
     * @param array $attributes Raw block attributes
     *
     * @return array Attributes with disk-relative media paths
     */
    private function normaliseAttributes(array $attributes): array
    {
        foreach (self::MEDIA_KEYS as $key) {
            if (! isset($attributes[$key]) || ! is_string($attributes[$key])) {
                continue;
            }

            $path = MediaUrl::path($attributes[$key]);

            if ($path !== null) {
                $attributes[$key] = $path;
            }
        }

        if (isset($attributes['images']) && is_array($attributes['images'])) {
            foreach ($attributes['images'] as &$image) {
                if (is_array($image) && isset($image['src']) && is_string($image['src'])) {
                    $path = MediaUrl::path($image['src']);

                    if ($path !== null) {
                        $image['src'] = $path;
                    }
                }
            }

            unset($image);
        }

        return $attributes;
    }

    /**
     * Determine whether a table and column exist in the current schema.
     *
     * @param string $table  Table name
     * @param string $column Column name
     */
    private function tableHasColumn(string $table, string $column): bool
    {
        return Schema::hasTable($table) && Schema::hasColumn($table, $column);
    }
};
