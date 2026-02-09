<?php

declare(strict_types=1);

namespace App\Infrastructure\Entity\Component;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
class SitemapSeoData
{
    #[ORM\Column]
    private bool $inSitemap = true;

    #[ORM\Column(length: 20)]
    private string $changefreq = 'weekly';

    #[ORM\Column(type: 'decimal', precision: 2, scale: 1)]
    private string $priority = '0.5';

    public function isInSitemap(): bool
    {
        return $this->inSitemap;
    }

    public function setInSitemap(bool $inSitemap): self
    {
        $this->inSitemap = $inSitemap;
        return $this;
    }

    public function getChangefreq(): string
    {
        return $this->changefreq;
    }

    public function setChangefreq(string $changefreq): self
    {
        $this->changefreq = $changefreq;
        return $this;
    }

    public function getPriority(): string
    {
        return $this->priority;
    }

    public function setPriority(string $priority): self
    {
        $this->priority = $priority;
        return $this;
    }
}
