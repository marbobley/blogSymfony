<?php

declare(strict_types=1);

namespace App\Domain\Model;

use App\Domain\Model\Component\CoreSeo;
use App\Domain\Model\Component\MetaSeo;
use App\Domain\Model\Component\SitemapSeo;
use App\Domain\Model\Component\SocialSeo;

final readonly class SeoModel
{
    public function __construct(
        private string $pageIdentifier,
        private CoreSeo $core,
        private SocialSeo $social,
        private SitemapSeo $sitemap,
        private MetaSeo $meta
    ) {
    }

    public function getPageIdentifier(): string
    {
        return $this->pageIdentifier;
    }

    public function getCore(): CoreSeo
    {
        return $this->core;
    }

    public function getSocial(): SocialSeo
    {
        return $this->social;
    }

    public function getSitemap(): SitemapSeo
    {
        return $this->sitemap;
    }

    public function getMeta(): MetaSeo
    {
        return $this->meta;
    }
}
