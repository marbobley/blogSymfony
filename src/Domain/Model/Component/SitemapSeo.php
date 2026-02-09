<?php

declare(strict_types=1);

namespace App\Domain\Model\Component;

final class SitemapSeo
{
    public function __construct(
        private bool $inSitemap = true,
        private string $changefreq = 'weekly',
        private float $priority = 0.5
    ) {
    }

    public function setInSitemap(bool $inSitemap): void
    {
        $this->inSitemap = $inSitemap;
    }

    public function setChangefreq(string $changefreq): void
    {
        $this->changefreq = $changefreq;
    }

    public function setPriority(float $priority): void
    {
        $this->priority = $priority;
    }

    public function isInSitemap(): bool
    {
        return $this->inSitemap;
    }

    public function getChangefreq(): string
    {
        return $this->changefreq;
    }

    public function getPriority(): float
    {
        return $this->priority;
    }
}
