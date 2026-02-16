<?php

declare(strict_types=1);

namespace App\Domain\Criteria;

final class PostCriteria
{
    public function __construct(
        private readonly ?int $tagId = null,
        private readonly ?string $search = null,
    ) {
    }

    public function getTagId(): ?int
    {
        return $this->tagId;
    }

    public function getSearch(): ?string
    {
        return $this->search;
    }
}
