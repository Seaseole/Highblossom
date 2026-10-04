<?php

declare(strict_types=1);

namespace Highblossom\ContentBlocks\Services;

use HTMLPurifier;
use HTMLPurifier_Config;

/**
 * Sanitizes HTML content using HTMLPurifier.
 */
final class HtmlSanitizer
{
    private ?HTMLPurifier $purifier = null;

    private ?HTMLPurifier $embedPurifier = null;

    /**
     * Sanitize the given HTML string.
     */
    public function sanitize(string $html): string
    {
        $this->ensurePurifierInitialized();

        return $this->purifier->purify($html);
    }

    /**
     * Sanitize embed markup, preserving provider iframes while stripping scripts
     * and unsafe attributes.
     */
    public function sanitizeEmbed(string $html): string
    {
        if ($this->embedPurifier === null) {
            $this->embedPurifier = new HTMLPurifier($this->buildEmbedConfig());
        }

        return $this->embedPurifier->purify($html);
    }

    /**
     * Ensure the HTMLPurifier instance is initialized.
     */
    private function ensurePurifierInitialized(): void
    {
        if ($this->purifier !== null) {
            return;
        }

        $config = HTMLPurifier_Config::createDefault();

        $config->set('HTML.Allowed', config('content-blocks.html.allowed_tags', 'p,br,strong,em,u,a[href|title],ul,ol,li,blockquote,code,pre,h1,h2,h3,h4,h5,h6,table,thead,tbody,tr,th,td,span,div,img[src|alt|title]'));

        $config->set('HTML.AllowedAttributes', config('content-blocks.html.allowed_attributes', 'href,title,src,alt,class,id'));

        $config->set('URI.AllowedSchemes', config('content-blocks.html.allowed_schemes', ['http', 'https', 'mailto']));

        $config->set('AutoFormat.RemoveEmpty', config('content-blocks.html.remove_empty', true));

        $config->set('AutoFormat.RemoveSpansWithoutAttributes', config('content-blocks.html.remove_empty_spans', true));

        $config->set('HTML.Doctype', 'XHTML 1.0 Transitional');

        $serializerPath = storage_path('framework/cache/htmlpurifier');

        if (! is_dir($serializerPath)) {
            mkdir($serializerPath, 0775, true);
        }

        $config->set('Cache.SerializerPath', $serializerPath);

        $this->purifier = new HTMLPurifier($config);
    }

    /**
     * Build the HTMLPurifier configuration used for embed markup.
     *
     * Extends the default definition with an iframe element so oEmbed players
     * survive sanitization, while scripts, event handlers, and inline styles
     * are still removed.
     */
    private function buildEmbedConfig(): HTMLPurifier_Config
    {
        $config = HTMLPurifier_Config::createDefault();

        $config->set('HTML.Doctype', 'XHTML 1.0 Transitional');

        $config->set('HTML.DefinitionID', 'content-blocks-embed');

        $config->set('Cache.DefinitionImpl', null);

        $config->set('HTML.Allowed', 'iframe[src|width|height|title|frameborder|allowfullscreen|allow|referrerpolicy|loading|sandbox],blockquote[cite],a[href|title],p,br,span,em,strong,cite');

        $config->set('URI.AllowedSchemes', ['http' => true, 'https' => true]);

        $definition = $config->maybeGetRawHTMLDefinition();

        if ($definition !== null) {
            $iframe = $definition->addElement(
                'iframe',
                'Block',
                'Optional: #PCDATA',
                'Common',
                [
                    'src' => 'URI',
                    'width' => 'Text',
                    'height' => 'Text',
                    'title' => 'Text',
                    'frameborder' => 'Text',
                    'allowfullscreen' => 'Bool',
                    'allow' => 'Text',
                    'referrerpolicy' => 'Text',
                    'loading' => 'Text',
                    'sandbox' => 'Text',
                ]
            );

            $iframe->excludes = ['%all'];
        }

        return $config;
    }
}
