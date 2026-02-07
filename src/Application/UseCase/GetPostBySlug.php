<?php

declare(strict_types=1);

namespace App\Application\UseCase;

use App\Application\Model\PostModel;
use App\Application\UseCaseInterface\GetPostBySlugInterface;
use App\Domain\Exception\EntityNotFoundException;
use App\Domain\Repository\PostRepositoryInterface;
use App\Infrastructure\MapperInterface\PostMapperInterface;

readonly class GetPostBySlug implements GetPostBySlugInterface
{
    public function __construct(
        private PostRepositoryInterface $postRepository,
        private PostMapperInterface $postMapper,
    ) {
    }

    public function execute(string $slug): PostModel
    {
        $post = $this->postRepository->findBySlug($slug);

        if (!$post) {
            throw EntityNotFoundException::forEntity('Post', $slug);
        }

        return $this->postMapper->toModel($post);
    }
}
