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
];
