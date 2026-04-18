<?php

declare(strict_types=1);

namespace App\Domains\Content\Blocks;

final class SeoBlock extends Block
{
    public static function id(): string
    {
        return 'seo';
    }

    public static function name(): string
    {
        return 'SEO Metadata';
    }

    public static function icon(): string
    {
        return 'search';
    }

    public static function category(): string
    {
        return 'layout';
    }

    public static function description(): string
    {
        return 'Add structured data markup for better SEO.';
    }

    public static function schema(): array
    {
        return [
            [
                'name' => 'schema_type',
                'type' => 'select',
                'label' => 'Schema Type',
                'required' => true,
                'options' => [
                    ['value' => 'Article', 'label' => 'Article'],
                    ['value' => 'BlogPosting', 'label' => 'Blog Post'],
                    ['value' => 'FAQPage', 'label' => 'FAQ Page'],
                    ['value' => 'HowTo', 'label' => 'How-To Guide'],
                    ['value' => 'LocalBusiness', 'label' => 'Local Business'],
                ],
                'default' => 'BlogPosting',
            ],
            [
                'name' => 'faq_items',
                'type' => 'repeater',
                'label' => 'FAQ Items (for FAQPage schema)',
                'required' => false,
                'fields' => [
                    ['name' => 'question', 'type' => 'text', 'label' => 'Question'],
                    ['name' => 'answer', 'type' => 'textarea', 'label' => 'Answer'],
                ],
            ],
        ];
    }

    public static function defaultData(): array
    {
        return [
            'schema_type' => 'BlogPosting',
            'faq_items' => [],
        ];
    }

    public static function validationRules(): array
    {
        return [
            'schema_type' => ['required', 'in:Article,BlogPosting,FAQPage,HowTo,LocalBusiness'],
            'faq_items' => ['nullable', 'array'],
            'faq_items.*.question' => ['required_with:faq_items', 'string'],
            'faq_items.*.answer' => ['required_with:faq_items', 'string'],
        ];
    }

    public static function component(): string
    {
        return 'blocks.seo';
    }

    /**
     * Generate JSON-LD structured data
     */
    public static function generateStructuredData(array $content, array $postData): ?string
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => $content['schema_type'] ?? 'BlogPosting',
        ];

        // Common properties
        if (!empty($postData['title'])) {
            $schema['headline'] = $postData['title'];
        }
        if (!empty($postData['excerpt'])) {
            $schema['description'] = $postData['excerpt'];
        }
        if (!empty($postData['published_at'])) {
            $schema['datePublished'] = $postData['published_at'];
        }
        if (!empty($postData['author'])) {
            $schema['author'] = [
                '@type' => 'Person',
                'name' => $postData['author'],
            ];
        }

        // FAQ schema
        if ($content['schema_type'] === 'FAQPage' && !empty($content['faq_items'])) {
            $schema['mainEntity'] = array_map(fn ($item) => [
                '@type' => 'Question',
                'name' => $item['question'],
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => $item['answer'],
                ],
            ], $content['faq_items']);
        }

        return json_encode($schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }
}
