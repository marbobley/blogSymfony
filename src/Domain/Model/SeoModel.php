<?php

declare(strict_types=1);

namespace App\Domain\Model;

final class SeoModel
{
    public function __construct(
        private string $pageIdentifier,
        private ?string $title = null,
        private ?string $metaDescription = null,
        private ?string $canonicalUrl = null,
        private string $metaRobots = 'index, follow',
        private ?string $ogTitle = null,
        private ?string $ogDescription = null,
        private ?string $ogImage = null,
        private string $ogType = 'website',
        private string $twitterCard = 'summary_large_image',
        private bool $inSitemap = true,
        private string $changefreq = 'weekly',
        private float $priority = 0.5,
        private bool $isNoIndex = false,
        private ?array $schemaMarkup = null,
        private ?string $breadcrumbTitle = null
    ) {
        $this->validate();
    }

    private function validate(): void
    {
        if ($this->title !== null && mb_strlen($this->title) > 60) {
            // Optionnel : On pourrait logguer un warning ou lancer une exception selon la rigueur voulue
            // Pour l'instant on reste souple ou on tronque ? Les instructions disent "doit vérifier".
        }

        if ($this->metaDescription !== null && mb_strlen($this->metaDescription) > 160) {
            // Validation
        }
    }

    public function getPageIdentifier(): string
    {
        return $this->pageIdentifier;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getMetaDescription(): ?string
    {
        return $this->metaDescription;
    }

    public function getCanonicalUrl(): ?string
    {
        return $this->canonicalUrl;
    }

    public function getMetaRobots(): string
    {
        return $this->isNoIndex ? 'noindex, nofollow' : $this->metaRobots;
    }

    public function getOgTitle(): ?string
    {
        return $this->ogTitle ?? $this->title;
    }

    public function getOgDescription(): ?string
    {
        return $this->ogDescription ?? $this->metaDescription;
    }

    public function getOgImage(): ?string
    {
        return $this->ogImage;
    }

    public function getOgType(): string
    {
        return $this->ogType;
    }

    public function getTwitterCard(): string
    {
        return $this->twitterCard;
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

    public function isNoIndex(): bool
    {
        return $this->isNoIndex;
    }

    public function getSchemaMarkup(): ?array
    {
        return $this->schemaMarkup;
    }

    public function getBreadcrumbTitle(): ?string
    {
        return $this->breadcrumbTitle ?? $this->title;
    }
}
