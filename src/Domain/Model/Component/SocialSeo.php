<?php

declare(strict_types=1);

namespace App\Domain\Model\Component;

final class SocialSeo
{
    public function __construct(
        private ?string $ogTitle = null,
        private ?string $ogDescription = null,
        private ?string $ogImage = null,
        private string $ogType = 'website',
        private string $twitterCard = 'summary_large_image'
    ) {
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

    public function getOgType(): string
    {
        return $this->ogType;
    }

    public function getTwitterCard(): string
    {
        return $this->twitterCard;
    }
}
