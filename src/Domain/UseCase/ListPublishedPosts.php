<?php

declare(strict_types=1);

namespace App\Domain\UseCase;

use App\Domain\Provider\PostProviderInterface;
use App\Domain\UseCaseInterface\ListPublishedPostsInterface;

readonly class ListPublishedPosts implements ListPublishedPostsInterface
{
    public function __construct(
        private PostProviderInterface $postProvider
    ) {
    }

    public function execute(?int $tagId = null, ?string $search = null): array
    {
        return $this->postProvider->findPublished($tagId, $search);
    }
}
