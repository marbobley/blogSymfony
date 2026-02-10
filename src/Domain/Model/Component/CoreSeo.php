<?php

declare(strict_types=1);

namespace App\Domain\Model\Component;

use App\Domain\Enum\RobotsMode;

final readonly class CoreSeo
{
    public function __construct(
        private ?string $title = null,
        private ?string $metaDescription = null,
        private ?string $canonicalUrl = null,
        private ?string $favicon = null,
        private RobotsMode $metaRobots = RobotsMode::INDEX_FOLLOW
    ) {
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

    public function getFavicon(): ?string
    {
        return $this->favicon;
    }

    public function getMetaRobots(): RobotsMode
    {
        return $this->metaRobots;
    }
}
