<?php

declare(strict_types=1);

namespace App\Domain\UseCase;

use App\Domain\Criteria\PostCriteria;
use App\Domain\Provider\PostProviderInterface;
use App\Domain\UseCaseInterface\ListPublishedPostsInterface;

readonly class ListPublishedPosts implements ListPublishedPostsInterface
{
    public function __construct(
        private PostProviderInterface $postProvider
    ) {
    }

    public function execute(?PostCriteria $criteria = null): array
    {
        return $this->postProvider->findPublishedByCriteria($criteria ?? new PostCriteria());
    }
}
