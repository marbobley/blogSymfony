<?php

declare(strict_types=1);

namespace App\Domain\Criteria;

final readonly class PostCriteria
{
    public function __construct(
        private ?int $tagId = null,
        private ?string $search = null,
        private bool $onlyPublished = false,
    ) {}

    public function getTagId(): ?int
    {
        return $this->tagId;
    }

    public function getSearch(): ?string
    {
        return $this->search;
    }

    public function isOnlyPublished(): bool
    {
        return $this->onlyPublished;
    }
}
