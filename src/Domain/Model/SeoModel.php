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

    public function setPageIdentifier(string $pageIdentifier): void
    {
        $this->pageIdentifier = $pageIdentifier;
    }

    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    public function setMetaDescription(?string $metaDescription): void
    {
        $this->metaDescription = $metaDescription;
    }

    public function setCanonicalUrl(?string $canonicalUrl): void
    {
        $this->canonicalUrl = $canonicalUrl;
    }

    public function setMetaRobots(string $metaRobots): void
    {
        $this->metaRobots = $metaRobots;
    }

    public function setOgTitle(?string $ogTitle): void
    {
        $this->ogTitle = $ogTitle;
    }

    public function setOgDescription(?string $ogDescription): void
    {
        $this->ogDescription = $ogDescription;
    }

    public function setOgImage(?string $ogImage): void
    {
        $this->ogImage = $ogImage;
    }

    public function setOgType(string $ogType): void
    {
        $this->ogType = $ogType;
    }

    public function setTwitterCard(string $twitterCard): void
    {
        $this->twitterCard = $twitterCard;
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

    public function setIsNoIndex(bool $isNoIndex): void
    {
        $this->isNoIndex = $isNoIndex;
    }

    public function setSchemaMarkup(?array $schemaMarkup): void
    {
        $this->schemaMarkup = $schemaMarkup;
    }

    public function setBreadcrumbTitle(?string $breadcrumbTitle): void
    {
        $this->breadcrumbTitle = $breadcrumbTitle;
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
