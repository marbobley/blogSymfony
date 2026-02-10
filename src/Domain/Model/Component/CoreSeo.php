<?php

declare(strict_types=1);

namespace App\Domain\Model\Component;

use App\Domain\Enum\RobotsMode;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final readonly class CoreSeo
{
    public function __construct(
        private ?string $title = null,
        private ?string $metaDescription = null,
        private ?string $canonicalUrl = null,
        private ?string $favicon = null,
        private RobotsMode $metaRobots = RobotsMode::INDEX_FOLLOW,
        private ?UploadedFile $faviconFile = null
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

    public function getFaviconFile(): ?UploadedFile
    {
        return $this->faviconFile;
    }

    public function getMetaRobots(): RobotsMode
    {
        return $this->metaRobots;
    }
}
