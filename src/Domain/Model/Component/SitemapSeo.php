<?php

declare(strict_types=1);

namespace App\Domain\Model\Component;

use App\Domain\Enum\ChangeFreq;
use InvalidArgumentException;

final readonly class SitemapSeo
{
    public function __construct(
        private bool $inSitemap = true,
        private ChangeFreq $changefreq = ChangeFreq::WEEKLY,
        private float $priority = 0.5
    ) {
        if ($this->priority < 0.0 || $this->priority > 1.0) {
            throw new InvalidArgumentException('Sitemap priority must be between 0.0 and 1.0');
        }
    }

    public function isInSitemap(): bool
    {
        return $this->inSitemap;
    }

    public function getChangefreq(): ChangeFreq
    {
        return $this->changefreq;
    }

    public function getPriority(): float
    {
        return $this->priority;
    }
}
