<?php

declare(strict_types=1);

namespace App\Infrastructure\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'seo_data')]
class SeoData
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    private string $pageIdentifier;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $title = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $metaDescription = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $canonicalUrl = null;

    #[ORM\Column(length: 50)]
    private string $metaRobots = 'index, follow';

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $ogTitle = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $ogDescription = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $ogImage = null;

    #[ORM\Column(length: 50)]
    private string $ogType = 'website';

    #[ORM\Column(length: 50)]
    private string $twitterCard = 'summary_large_image';

    #[ORM\Column]
    private bool $inSitemap = true;

    #[ORM\Column(length: 20)]
    private string $changefreq = 'weekly';

    #[ORM\Column(type: 'decimal', precision: 2, scale: 1)]
    private string $priority = '0.5';

    #[ORM\Column]
    private bool $isNoIndex = false;

    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $schemaMarkup = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $breadcrumbTitle = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPageIdentifier(): string
    {
        return $this->pageIdentifier;
    }

    public function setPageIdentifier(string $pageIdentifier): self
    {
        $this->pageIdentifier = $pageIdentifier;
        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;
        return $this;
    }

    public function getMetaDescription(): ?string
    {
        return $this->metaDescription;
    }

    public function setMetaDescription(?string $metaDescription): self
    {
        $this->metaDescription = $metaDescription;
        return $this;
    }

    public function getCanonicalUrl(): ?string
    {
        return $this->canonicalUrl;
    }

    public function setCanonicalUrl(?string $canonicalUrl): self
    {
        $this->canonicalUrl = $canonicalUrl;
        return $this;
    }

    public function getMetaRobots(): string
    {
        return $this->metaRobots;
    }

    public function setMetaRobots(string $metaRobots): self
    {
        $this->metaRobots = $metaRobots;
        return $this;
    }

    public function getOgTitle(): ?string
    {
        return $this->ogTitle;
    }

    public function setOgTitle(?string $ogTitle): self
    {
        $this->ogTitle = $ogTitle;
        return $this;
    }

    public function getOgDescription(): ?string
    {
        return $this->ogDescription;
    }

    public function setOgDescription(?string $ogDescription): self
    {
        $this->ogDescription = $ogDescription;
        return $this;
    }

    public function getOgImage(): ?string
    {
        return $this->ogImage;
    }

    public function setOgImage(?string $ogImage): self
    {
        $this->ogImage = $ogImage;
        return $this;
    }

    public function getOgType(): string
    {
        return $this->ogType;
    }

    public function setOgType(string $ogType): self
    {
        $this->ogType = $ogType;
        return $this;
    }

    public function getTwitterCard(): string
    {
        return $this->twitterCard;
    }

    public function setTwitterCard(string $twitterCard): self
    {
        $this->twitterCard = $twitterCard;
        return $this;
    }

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

    public function isNoIndex(): bool
    {
        return $this->isNoIndex;
    }

    public function setIsNoIndex(bool $isNoIndex): self
    {
        $this->isNoIndex = $isNoIndex;
        return $this;
    }

    public function getSchemaMarkup(): ?array
    {
        return $this->schemaMarkup;
    }

    public function setSchemaMarkup(?array $schemaMarkup): self
    {
        $this->schemaMarkup = $schemaMarkup;
        return $this;
    }

    public function getBreadcrumbTitle(): ?string
    {
        return $this->breadcrumbTitle;
    }

    public function setBreadcrumbTitle(?string $breadcrumbTitle): self
    {
        $this->breadcrumbTitle = $breadcrumbTitle;
        return $this;
    }
}
