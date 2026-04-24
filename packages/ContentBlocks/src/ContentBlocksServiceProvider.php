<?php

namespace Highblossom\ContentBlocks;

use Highblossom\ContentBlocks\Services\BlockRegistry;
use Highblossom\ContentBlocks\Services\BlockRenderer;
use Highblossom\ContentBlocks\Blocks\ParagraphBlock;
use Highblossom\ContentBlocks\Blocks\ImageBlock;
use Highblossom\ContentBlocks\Blocks\HeadingBlock;
use Highblossom\ContentBlocks\Blocks\QuoteBlock;
use Highblossom\ContentBlocks\Blocks\CodeBlock;
use Highblossom\ContentBlocks\Blocks\ListBlock;
use Highblossom\ContentBlocks\Blocks\CTABlock;
use Highblossom\ContentBlocks\Blocks\VideoBlock;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class ContentBlocksServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register BlockRegistry as singleton
        $this->app->singleton(BlockRegistry::class, function ($app) {
            return new BlockRegistry();
        });

        // Register BlockRenderer as singleton
        $this->app->singleton(BlockRenderer::class, function ($app) {
            return new BlockRenderer($app->make(BlockRegistry::class));
        });

        // Register block services as singletons
        $this->registerBlockServices();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Load package views
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'content-blocks');

        // Publish configuration
        $this->publishes([
            __DIR__.'/../config/content-blocks.php' => config_path('content-blocks.php'),
        ], 'content-blocks-config');

        // Auto-register default blocks FIRST
        $this->autoRegisterBlocks();

        // Register Blade directives SECOND (needs blocks to be registered first)
        $this->registerBladeDirectives();
    }

    /**
     * Register block services as singletons.
     */
    protected function registerBlockServices(): void
    {
        $this->app->singleton(ParagraphBlock::class);
        $this->app->singleton(ImageBlock::class);
        $this->app->singleton(HeadingBlock::class);
        $this->app->singleton(QuoteBlock::class);
        $this->app->singleton(CodeBlock::class);
        $this->app->singleton(ListBlock::class);
        $this->app->singleton(CTABlock::class);
        $this->app->singleton(VideoBlock::class);
    }

    /**
     * Auto-register default blocks with the registry.
     */
    protected function autoRegisterBlocks(): void
    {
        $registry = $this->app->make(BlockRegistry::class);

        $registry->register('paragraph', $this->app->make(ParagraphBlock::class));
        $registry->register('image', $this->app->make(ImageBlock::class));
        $registry->register('heading', $this->app->make(HeadingBlock::class));
        $registry->register('quote', $this->app->make(QuoteBlock::class));
        $registry->register('code', $this->app->make(CodeBlock::class));
        $registry->register('list', $this->app->make(ListBlock::class));
        $registry->register('cta', $this->app->make(CTABlock::class));
        $registry->register('video', $this->app->make(VideoBlock::class));
    }

    /**
     * Register Blade directives for block rendering.
     */
    protected function registerBladeDirectives(): void
    {
        $registry = $this->app->make(BlockRegistry::class);

        // Register directive for each block type
        foreach ($registry->types() as $type) {
            Blade::directive($this->getDirectiveName($type), function ($expression) use ($type) {
                return "<?php echo app('Highblossom\\\\ContentBlocks\\\\Services\\\\BlockRegistry')->render('{$type}', {$expression}); ?>";
            });
        }

        // Generic block directive - passes type and attributes separately
        Blade::directive('block', function ($expression) {
            // Extract type and attributes from expression like "($type, $attributes)"
            $inner = trim($expression, '()');
            $parts = array_map('trim', explode(',', $inner, 2));
            $type = $parts[0] ?? '';
            $attributes = $parts[1] ?? '[]';
            return "<?php echo app('Highblossom\\ContentBlocks\\Services\\BlockRegistry')->render({$type}, {$attributes}); ?>";
        });
    }

    /**
     * Get the directive name for a block type.
     *
     * @param string $type
     * @return string
     */
    protected function getDirectiveName(string $type): string
    {
        $prefix = config('content-blocks.directive_prefix', 'cb');
        return $prefix . ucfirst($type);
    }
}
