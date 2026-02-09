<?php

declare(strict_types=1);

namespace App\Infrastructure\Entity\Component;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
class SocialSeoData
{
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
}
