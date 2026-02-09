<?php

declare(strict_types=1);

namespace App\Domain\Model\Component;

final readonly class MetaSeo
{
    public function __construct(
        private bool $isNoIndex = false,
        private ?array $schemaMarkup = null,
        private ?string $breadcrumbTitle = null
    ) {
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
        return $this->breadcrumbTitle;
    }
}
