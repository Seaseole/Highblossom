<?php

namespace Highblossom\ContentBlocks;

use Highblossom\ContentBlocks\Services\BlockRegistry;
use Highblossom\ContentBlocks\Services\BlockRenderer;
use Highblossom\ContentBlocks\Services\HtmlSanitizer;
use Highblossom\ContentBlocks\Services\OEmbedResolver;
use Highblossom\ContentBlocks\Blocks\ParagraphBlock;
use Highblossom\ContentBlocks\Blocks\ImageBlock;
use Highblossom\ContentBlocks\Blocks\HeadingBlock;
use Highblossom\ContentBlocks\Blocks\QuoteBlock;
use Highblossom\ContentBlocks\Blocks\CodeBlock;
use Highblossom\ContentBlocks\Blocks\ListBlock;
use Highblossom\ContentBlocks\Blocks\CTABlock;
use Highblossom\ContentBlocks\Blocks\VideoBlock;
use Highblossom\ContentBlocks\Blocks\DividerBlock;
use Highblossom\ContentBlocks\Blocks\AlertBlock;
use Highblossom\ContentBlocks\Blocks\HtmlBlock;
use Highblossom\ContentBlocks\Blocks\EmbedBlock;
use Highblossom\ContentBlocks\Blocks\AccordionBlock;
use Highblossom\ContentBlocks\Blocks\TableBlock;
use Highblossom\ContentBlocks\Blocks\GalleryBlock;
use Highblossom\ContentBlocks\Blocks\FormBlock;
use Highblossom\ContentBlocks\Blocks\ColumnsBlock;
use Highblossom\ContentBlocks\Blocks\TabsBlock;
use Highblossom\ContentBlocks\Blocks\CarouselBlock;
use Highblossom\ContentBlocks\Blocks\CountdownBlock;
use Highblossom\ContentBlocks\Blocks\PollBlock;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class ContentBlocksServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->registerServices();
        $this->registerBlockServices();
        $this->registerHtmlSanitizer();
        $this->registerOEmbedResolver();
    }

    /**
     * Register services.
     */
    protected function registerServices(): void
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
        $this->app->singleton(DividerBlock::class);
        $this->app->singleton(AlertBlock::class);
        $this->app->singleton(HtmlBlock::class);
        $this->app->singleton(EmbedBlock::class);
        $this->app->singleton(AccordionBlock::class);
        $this->app->singleton(TableBlock::class);
        $this->app->singleton(GalleryBlock::class);
        $this->app->singleton(FormBlock::class);
        $this->app->singleton(ColumnsBlock::class);
        $this->app->singleton(TabsBlock::class);
        $this->app->singleton(CarouselBlock::class);
        $this->app->singleton(CountdownBlock::class);
        $this->app->singleton(PollBlock::class);
    }

    protected function registerHtmlSanitizer(): void
    {
        $this->app->singleton(HtmlSanitizer::class);
    }

    protected function registerOEmbedResolver(): void
    {
        $this->app->singleton(OEmbedResolver::class);
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
        $registry->register('divider', $this->app->make(DividerBlock::class));
        $registry->register('alert', $this->app->make(AlertBlock::class));
        $registry->register('html', $this->app->make(HtmlBlock::class));
        $registry->register('embed', $this->app->make(EmbedBlock::class));
        $registry->register('accordion', $this->app->make(AccordionBlock::class));
        $registry->register('table', $this->app->make(TableBlock::class));
        $registry->register('gallery', $this->app->make(GalleryBlock::class));
        $registry->register('form', $this->app->make(FormBlock::class));
        $registry->register('columns', $this->app->make(ColumnsBlock::class));
        $registry->register('tabs', $this->app->make(TabsBlock::class));
        $registry->register('carousel', $this->app->make(CarouselBlock::class));
        $registry->register('countdown', $this->app->make(CountdownBlock::class));
        $registry->register('poll', $this->app->make(PollBlock::class));
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
