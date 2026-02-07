<?php

declare(strict_types=1);

namespace App\Application\UseCase;

use App\Application\DTO\PostResponseDTO;
use App\Application\Factory\PostResponseDTOFactory;
use App\Application\UseCaseInterface\GetPostInterface;
use App\Domain\Exception\EntityNotFoundException;
use App\Domain\Repository\PostRepositoryInterface;

readonly class GetPost implements GetPostInterface
{
    public function __construct(
        private PostRepositoryInterface $postRepository
    ) {
    }

    public function execute(int $id): PostResponseDTO
    {
        $post = $this->getById($id);

        return PostResponseDTOFactory::createFromEntity($post);
    }

    public function getById(int $id): \App\Domain\Model\Post
    {
        $post = $this->postRepository->findById($id);

        if (!$post) {
            throw EntityNotFoundException::forEntity('Post', $id);
        }

        return $post;
    }
}
