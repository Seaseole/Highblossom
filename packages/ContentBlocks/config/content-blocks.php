<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Directive Prefix
    |--------------------------------------------------------------------------
    |
    | The prefix to use for Blade directives. For example, with a prefix of 'cb',
    | the paragraph block directive would be @cbParagraph.
    |
    */
    'directive_prefix' => env('CONTENT_BLOCKS_DIRECTIVE_PREFIX', 'cb'),

    /*
    |--------------------------------------------------------------------------
    | View Namespace
    |--------------------------------------------------------------------------
    |
    | The view namespace used for block templates. You can override individual
    | block views by publishing them to your resources/views directory.
    |
    */
    'view_namespace' => 'content-blocks',

    /*
    |--------------------------------------------------------------------------
    | Auto-Register Blocks
    |--------------------------------------------------------------------------
    |
    | Whether to automatically register the default block types. Set to false
    | if you want to manually register only the blocks you need.
    |
    */
    'auto_register' => env('CONTENT_BLOCKS_AUTO_REGISTER', true),

    /*
    |--------------------------------------------------------------------------
    | Cache Block Registry
    |--------------------------------------------------------------------------
    |
    | Whether to cache the block registry for better performance. Disable this
    | during development if you're frequently adding new block types.
    |
    */
    'cache_registry' => env('CONTENT_BLOCKS_CACHE_REGISTRY', false),

    /*
    |--------------------------------------------------------------------------
    | Debug Mode
    |--------------------------------------------------------------------------
    |
    | When enabled, invalid blocks will render HTML comments with error details.
    | When disabled (production), invalid blocks render empty strings.
    |
    */
    'debug_mode' => env('CONTENT_BLOCKS_DEBUG_MODE', false),

    /*
    |--------------------------------------------------------------------------
    | Temp Upload Cleanup
    |--------------------------------------------------------------------------
    |
    | Configuration for automatic cleanup of orphaned temp upload files.
    |
    */
    'temp_cleanup' => [
        'enabled' => env('CONTENT_BLOCKS_TEMP_CLEANUP_ENABLED', true),
        'retention_hours' => env('CONTENT_BLOCKS_TEMP_CLEANUP_RETENTION_HOURS', 24),
        'schedule' => env('CONTENT_BLOCKS_TEMP_CLEANUP_SCHEDULE', 'daily'),
    ],

    /*
    |--------------------------------------------------------------------------
    | HTML Sanitization
    |--------------------------------------------------------------------------
    |
    | Configuration for HTML sanitization using HTMLPurifier.
    | Used by the HTML block to sanitize user-provided HTML content.
    |
    */
    'html' => [
        'allowed_tags' => env('CONTENT_BLOCKS_HTML_ALLOWED_TAGS', 'p,br,strong,em,u,a[href|title],ul,ol,li,blockquote,code,pre,h1,h2,h3,h4,h5,h6,table,thead,tbody,tr,th,td,span,div,img[src|alt|title]'),
        'allowed_attributes' => env('CONTENT_BLOCKS_HTML_ALLOWED_ATTRIBUTES', 'href,title,src,alt,class,id'),
        'allowed_schemes' => env('CONTENT_BLOCKS_HTML_ALLOWED_SCHEMES', 'http,https,mailto'),
        'remove_empty' => env('CONTENT_BLOCKS_HTML_REMOVE_EMPTY', true),
        'remove_empty_spans' => env('CONTENT_BLOCKS_HTML_REMOVE_EMPTY_SPANS', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | oEmbed Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for oEmbed resolution used by the Embed block.
    | Allows embedding content from YouTube, Vimeo, Twitter/X, Instagram, etc.
    |
    */
    'oembed' => [
        'max_width' => env('CONTENT_BLOCKS_OEMBED_MAX_WIDTH', 800),
        'max_height' => env('CONTENT_BLOCKS_OEMBED_MAX_HEIGHT', 600),
        'providers' => [
            [
                'name' => 'YouTube',
                'patterns' => ['/(youtube\.com|youtu\.be)/i'],
                'endpoint' => env('CONTENT_BLOCKS_OEMBED_YOUTUBE_ENDPOINT', 'https://www.youtube.com/oembed'),
            ],
            [
                'name' => 'Vimeo',
                'patterns' => ['/vimeo\.com/i'],
                'endpoint' => env('CONTENT_BLOCKS_OEMBED_VIMEO_ENDPOINT', 'https://vimeo.com/api/oembed.json'),
            ],
            [
                'name' => 'Twitter/X',
                'patterns' => ['/(twitter\.com|x\.com)/i'],
                'endpoint' => env('CONTENT_BLOCKS_OEMBED_TWITTER_ENDPOINT', 'https://publish.twitter.com/oembed'),
            ],
            [
                'name' => 'Instagram',
                'patterns' => ['/instagram\.com/i'],
                'endpoint' => env('CONTENT_BLOCKS_OEMBED_INSTAGRAM_ENDPOINT', 'https://www.instagram.com/oembed'),
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Form Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for form block handling.
    | Used by the Form block to configure submission behavior.
    |
    */
    'form' => [
        'default_submit_text' => env('CONTENT_BLOCKS_FORM_DEFAULT_SUBMIT_TEXT', 'Submit'),
        'enable_event_dispatch' => env('CONTENT_BLOCKS_FORM_ENABLE_EVENT_DISPATCH', true),
        'event_name' => env('CONTENT_BLOCKS_FORM_EVENT_NAME', 'content-blocks.form.submitted'),
    ],
];
