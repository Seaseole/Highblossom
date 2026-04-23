# Content Blocks

A Laravel content block template system with singleton service classes, Blade directives, and auto-registration for easy extensibility.

## Features

- **Singleton Block Services**: Each block type is registered as a singleton for optimal performance
- **Blade Directives**: Render blocks using intuitive Blade directives (e.g., `@cbParagraph`, `@cbImage`)
- **Auto-Registration**: Blocks are automatically registered via a single Service Provider
- **Extensible**: Add new block types with minimal changes to existing code
- **Validation**: Built-in attribute validation using Laravel's validator
- **Type Casting**: Automatic type casting for block attributes

## Installation

### Local Development

Add the package to your main project's `composer.json`:

```json
{
    "repositories": [
        {
            "type": "path",
            "url": "packages/ContentBlocks"
        }
    ],
    "require": {
        "highblossom/content-blocks": "*"
    }
}
```

Then run:

```bash
composer update
```

### Publish Configuration

```bash
php artisan vendor:publish --tag=content-blocks-config
```

## Usage

### Using Blade Directives

Render blocks directly in your Blade templates using the auto-generated directives:

```blade
@cbParagraph(['content' => 'Hello, world!', 'class' => 'text-lg'])

@cbHeading(['content' => 'Welcome', 'level' => 2, 'class' => 'text-2xl'])

@cbImage(['src' => '/images/photo.jpg', 'alt' => 'Description', 'caption' => 'Photo caption'])

@cbQuote(['content' => 'Quote text', 'author' => 'Author Name', 'cite' => 'Source'])

@cbCode(['content' => 'console.log("Hello");', 'language' => 'javascript'])

@cbList(['items' => ['Item 1', 'Item 2', 'Item 3'], 'type' => 'unordered'])

@cbCta(['title' => 'Get Started', 'button_text' => 'Click Here', 'button_url' => '/signup'])

@cbVideo(['src' => '/videos/video.mp4', 'controls' => true])
```

### Using the Facade

```php
use Highblossom\ContentBlocks\Facades\ContentBlocks;

// Render a single block
$html = ContentBlocks::render('paragraph', ['content' => 'Hello, world!']);

// Check if a block type exists
if (ContentBlocks::has('paragraph')) {
    // ...
}

// Get all registered block types
$types = ContentBlocks::types();
```

### Using the BlockRenderer Service

```php
use Highblossom\ContentBlocks\Services\BlockRenderer;

$renderer = app(BlockRenderer::class);

// Render a single block
$html = $renderer->render('paragraph', ['content' => 'Hello']);

// Render multiple blocks
$blocks = [
    ['type' => 'heading', 'attributes' => ['content' => 'Title', 'level' => 1]],
    ['type' => 'paragraph', 'attributes' => ['content' => 'Content']],
];
$html = $renderer->renderMany($blocks);
```

## Available Block Types

### Paragraph
- `content` (required): The paragraph text
- `class` (optional): CSS class names

### Image
- `src` (required): Image source URL
- `alt` (optional): Alt text
- `caption` (optional): Image caption
- `width` (optional): Image width in pixels
- `height` (optional): Image height in pixels
- `class` (optional): CSS class names

### Heading
- `content` (required): Heading text
- `level` (required): Heading level (1-6)
- `class` (optional): CSS class names

### Quote
- `content` (required): Quote text
- `author` (optional): Author name
- `cite` (optional): Citation URL
- `class` (optional): CSS class names

### Code
- `content` (required): Code content
- `language` (optional): Programming language
- `class` (optional): CSS class names

### List
- `items` (required): Array of list items
- `type` (required): List type ('ordered' or 'unordered')
- `class` (optional): CSS class names

### CTA (Call to Action)
- `title` (required): CTA title
- `description` (optional): CTA description
- `button_text` (required): Button text
- `button_url` (required): Button URL
- `class` (optional): CSS class names

### Video
- `src` (required): Video source URL
- `type` (optional): Video MIME type
- `poster` (optional): Poster image URL
- `autoplay` (optional): Autoplay video (default: false)
- `controls` (optional): Show controls (default: true)
- `class` (optional): CSS class names

## Creating Custom Blocks

To create a custom block type:

1. Create a new block class extending `AbstractBlock`:

```php
<?php

namespace App\Blocks;

use Highblossom\ContentBlocks\Services\AbstractBlock;

class CustomBlock extends AbstractBlock
{
    public function getType(): string
    {
        return 'custom';
    }

    public function getValidationRules(): array
    {
        return [
            'title' => 'required|string',
            'content' => 'required|string',
        ];
    }

    public function getDefaultAttributes(): array
    {
        return [
            'title' => '',
            'content' => '',
        ];
    }
}
```

2. Register the block in a Service Provider:

```php
use App\Blocks\CustomBlock;

public function boot(): void
{
    $registry = app(\Highblossom\ContentBlocks\Services\BlockRegistry::class);
    $registry->register('custom', new CustomBlock());
}
```

3. Create the Blade view at `resources/views/vendor/content-blocks/custom.blade.php`:

```blade
<div class="{{ $class ?? '' }}">
    <h3>{{ $title }}</h3>
    <p>{{ $content }}</p>
</div>
```

## Configuration

Publish the configuration file to customize behavior:

```bash
php artisan vendor:publish --tag=content-blocks-config
```

Available configuration options:

- `directive_prefix`: Prefix for Blade directives (default: 'cb')
- `view_namespace`: View namespace for block templates (default: 'content-blocks')
- `auto_register`: Whether to auto-register default blocks (default: true)
- `cache_registry`: Whether to cache the block registry (default: false)

## Testing

```bash
composer test
```

## License

MIT
