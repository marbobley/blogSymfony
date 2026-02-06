<?php

declare(strict_types=1);

namespace App\Application\UseCase;

use App\Application\DTO\PostDTO;
use App\Application\UseCaseInterface\UpdatePostInterface;
use App\Domain\Model\Post;
use App\Domain\Repository\PostRepositoryInterface;

class UpdatePost implements UpdatePostInterface
{
    public function __construct(
        private readonly PostRepositoryInterface $postRepository
    ) {
    }

    public function execute(int $id, PostDTO $postDTO): Post
    {
        $post = $this->postRepository->findById($id);

        if (!$post) {
            throw new \RuntimeException('Post not found');
        }

        $post->update($postDTO->title, $postDTO->content);

        $this->postRepository->save($post);

        return $post;
    }
}
