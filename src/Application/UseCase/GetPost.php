<?php

declare(strict_types=1);

namespace App\Application\UseCase;

use App\Application\DTO\PostResponseDTO;
use App\Application\UseCaseInterface\GetPostInterface;
use App\Domain\Repository\PostRepositoryInterface;

readonly class GetPost implements GetPostInterface
{
    public function __construct(
        private PostRepositoryInterface $postRepository
    ) {
    }

    public function execute(int $id): PostResponseDTO
    {
        $post = $this->postRepository->findById($id);

        if (!$post) {
            throw new \RuntimeException('Post not found');
        }

        return new PostResponseDTO(
            $post->getId(),
            $post->getTitle(),
            $post->getContent(),
            $post->getCreatedAt()
        );
    }
}
