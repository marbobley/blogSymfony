<?php

declare(strict_types=1);

namespace App\Infrastructure\Entity\Component;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
class MetaSeoData
{
    #[ORM\Column]
    private bool $isNoIndex = false;

    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $schemaMarkup = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $breadcrumbTitle = null;

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
