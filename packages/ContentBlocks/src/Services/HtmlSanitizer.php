<?php

declare(strict_types=1);

namespace Highblossom\ContentBlocks\Services;

use HTMLPurifier;
use HTMLPurifier_Config;

final class HtmlSanitizer
{
    private ?HTMLPurifier $purifier = null;

    public function sanitize(string $html): string
    {
        $this->ensurePurifierInitialized();

        return $this->purifier->purify($html);
    }

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

        $config->set('Cache.SerializerPath', storage_path('framework/cache/htmlpurifier'));

        $this->purifier = new HTMLPurifier($config);
    }
}
