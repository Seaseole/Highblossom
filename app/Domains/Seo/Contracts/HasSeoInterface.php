<?php

declare(strict_types=1);

namespace App\Domains\Seo\Contracts;

use App\Domains\Seo\DataTransferObjects\SeoMetadata;

interface HasSeoInterface
{
    public function getSeoMetadata(): SeoMetadata;

    public function updateSeo(array $data): void;

    public function seoDefaults(): array;

    public function getCanonicalUrl(): string;

    public function getSeoTitle(): string;

    public function getSeoDescription(): string;

    public function shouldIndex(): bool;
}
