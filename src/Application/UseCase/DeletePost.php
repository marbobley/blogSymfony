<?php

declare(strict_types=1);

namespace App\Application\UseCase;

use App\Application\UseCaseInterface\DeletePostInterface;
use App\Domain\Exception\EntityNotFoundException;
use App\Domain\Repository\PostRepositoryInterface;

class DeletePost implements DeletePostInterface
{
    public function __construct(
        private readonly PostRepositoryInterface $postRepository
    ) {
    }

    public function execute(int $id): void
    {
        $post = $this->postRepository->findById($id);

        if (!$post) {
            throw EntityNotFoundException::forEntity('Post', $id);
        }

        $this->postRepository->delete($post);
    }
}
