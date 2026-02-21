<?php

declare(strict_types=1);

namespace App\Domain\UseCase\Post;

use App\Domain\Criteria\PostCriteria;
use App\Domain\Provider\PostProviderInterface;
use App\Domain\UseCaseInterface\Post\ListAllPostsInterface;

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
