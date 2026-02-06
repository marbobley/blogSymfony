<?php

declare(strict_types=1);

namespace App\Application\UseCase;

use App\Application\DTO\PostResponseDTO;
use App\Application\UseCaseInterface\ListPostsInterface;
use App\Domain\Repository\PostRepositoryInterface;

readonly class ListPosts implements ListPostsInterface
{
    public function __construct(
        private PostRepositoryInterface $postRepository
    ) {
    }

    public function execute(): array
    {
        $posts = $this->postRepository->findAll();

        return array_map(function ($post) {
            return new PostResponseDTO(
                $post->getId(),
                $post->getTitle(),
                $post->getContent(),
                $post->getCreatedAt()
            );
        }, $posts);
    }
}
