<?php

declare(strict_types=1);

namespace App\Domain\Model\Component;

final class CoreSeo
{
    public function __construct(
        private ?string $title = null,
        private ?string $metaDescription = null,
        private ?string $canonicalUrl = null,
        private string $metaRobots = 'index, follow'
    ) {
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
        return $this->metaRobots;
    }
}
