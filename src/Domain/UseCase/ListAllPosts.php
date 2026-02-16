<?php

declare(strict_types=1);

namespace App\Domain\UseCase;

use App\Domain\Criteria\PostCriteria;
use App\Domain\Provider\PostProviderInterface;
use App\Domain\UseCaseInterface\ListAllPostsInterface;

readonly class ListAllPosts implements ListAllPostsInterface
{
    public function __construct(
        private PostProviderInterface $postProvider,
    ) {}

    public function execute(?PostCriteria $criteria = null): array
    {
        return $this->postProvider->findByCriteria($criteria ?? new PostCriteria());
    }
}
