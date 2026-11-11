<?php

declare(strict_types=1);

namespace App\Actions\Content;

use App\Concerns\PreparesAiPromptText;
use App\Models\SeoStaticRoute;
use App\Services\Ai\DataTransferObjects\CopySuggestionContext;
use App\Services\SeoService;

/**
 * Turn an SEO form's current field values into a bounded, redacted brief.
 *
 * The page label is resolved on the server from the route registry — by record
 * ID on the edit form, by allowlisted route name on the create form — so a
 * crafted request cannot attach an arbitrary subject to the prompt. With
 * neither present the brief describes the site-wide defaults.
 */
final class BuildSeoMetaSuggestionContextAction
{
    use PreparesAiPromptText;

    /**
     * @param SeoService $seo Resolves route names to human page labels.
     */
    public function __construct(private readonly SeoService $seo) {}

    /**
     * Build the context from validated request data.
     *
     * @param array<string, mixed> $data Validated SEO meta suggestion payload.
     *
     * @return CopySuggestionContext The bounded brief.
     */
    public function __invoke(array $data): CopySuggestionContext
    {
        $field = (string) ($data['field'] ?? 'meta_title');
        $siteName = $this->clean((string) config('seo.site_name', config('app.name')));
        $subject = $this->subject($data);

        return new CopySuggestionContext(
            kind: $field,
            subject: $subject !== '' ? $subject : $siteName,
            body: '',
            current: $this->clean((string) ($data[$field] ?? '')),
            keywords: $this->clean((string) ($data['meta_keywords'] ?? '')),
            siteName: $siteName,
            locale: app()->getLocale(),
            tone: (string) ($data['tone'] ?? ''),
        );
    }

    /**
     * Resolve the page label from the record or the allowlisted route name.
     *
     * @param array<string, mixed> $data Validated request data.
     *
     * @return string The redacted page label, or '' for site-wide defaults.
     */
    private function subject(array $data): string
    {
        $routeId = (int) ($data['route_id'] ?? 0);

        if ($routeId > 0) {
            $route = SeoStaticRoute::query()->find($routeId);

            if ($route !== null) {
                return $this->clean($this->seo->getRouteLabel((string) $route->route_name));
            }
        }

        $routeName = (string) ($data['route_name'] ?? '');

        if ($routeName !== '') {
            return $this->clean($this->seo->getRouteLabel($routeName));
        }

        return '';
    }
}
