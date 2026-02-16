<?php

declare(strict_types=1);

namespace App\Domain\Criteria;

final class PostCriteria
{
    public function __construct(
        private readonly ?int $tagId = null,
        private readonly ?string $search = null,
        private readonly bool $onlyPublished = false,
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
