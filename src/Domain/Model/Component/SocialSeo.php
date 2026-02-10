<?php

declare(strict_types=1);

namespace App\Domain\Model\Component;

use App\Domain\Enum\OgType;
use App\Domain\Enum\TwitterCard;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final readonly class SocialSeo
{
    public function __construct(
        private ?string $ogTitle = null,
        private ?string $ogDescription = null,
        private ?string $ogImage = null,
        private OgType $ogType = OgType::WEBSITE,
        private TwitterCard $twitterCard = TwitterCard::SUMMARY_LARGE_IMAGE,
        private ?UploadedFile $ogImageFile = null
    ) {
    }

    public function getOgTitle(): ?string
    {
        return $this->ogTitle;
    }

    public function getOgDescription(): ?string
    {
        return $this->ogDescription;
    }

    public function getOgImage(): ?string
    {
        return $this->ogImage;
    }

    public function getOgImageFile(): ?UploadedFile
    {
        return $this->ogImageFile;
    }

    public function getOgType(): OgType
    {
        return $this->ogType;
    }

    public function getTwitterCard(): TwitterCard
    {
        return $this->twitterCard;
    }
}
