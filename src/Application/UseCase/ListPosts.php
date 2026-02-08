<?php

declare(strict_types=1);

namespace App\Application\UseCase;

use App\Application\Provider\PostProviderInterface;
use App\Application\UseCaseInterface\ListPostsInterface;

readonly class ListPosts implements ListPostsInterface
{
    public function __construct(
        private PostProviderInterface $postProvider
    ) {
    }

    public function execute(?int $tagId = null): array
    {
        return $this->postProvider->findByTag($tagId);
    }
}
