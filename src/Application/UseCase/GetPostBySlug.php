<?php

declare(strict_types=1);

namespace App\Application\UseCase;

use App\Application\DTO\PostResponseDTO;
use App\Application\Factory\PostResponseDTOFactory;
use App\Application\UseCaseInterface\GetPostBySlugInterface;
use App\Domain\Repository\PostRepositoryInterface;

readonly class GetPostBySlug implements GetPostBySlugInterface
{
    public function __construct(
        private PostRepositoryInterface $postRepository
    ) {
    }

    public function execute(string $slug): PostResponseDTO
    {
        $post = $this->postRepository->findBySlug($slug);

        if (!$post) {
            throw new \RuntimeException('Post not found');
        }

        return PostResponseDTOFactory::createFromEntity($post);
    }
}
