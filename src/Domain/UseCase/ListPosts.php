<?php

declare(strict_types=1);

namespace App\Domain\UseCase;

use App\Domain\Provider\PostProviderInterface;
use App\Domain\UseCaseInterface\ListPostsInterface;

readonly class ListPosts implements ListPostsInterface
{
    public function __construct(
        private PostProviderInterface $postProvider
    ) {
    }

    public function execute(?int $tagId = null, bool $onlyPublished = true): array
    {
        if ($onlyPublished) {
            return $this->postProvider->findPublished($tagId);
        }
        return $this->postProvider->findByTag($tagId);
    }
}
