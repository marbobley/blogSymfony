<?php

declare(strict_types=1);

namespace App\Application\UseCase;

use App\Application\DTO\PostDTO;
use App\Application\UseCaseInterface\CreatePostInterface;
use App\Domain\Model\Post;
use App\Domain\Repository\PostRepositoryInterface;

class CreatePost implements CreatePostInterface
{
    public function __construct(
        private readonly PostRepositoryInterface $postRepository
    ) {
    }

    public function execute(PostDTO $postDTO): Post
    {
        $post = new Post($postDTO->title, $postDTO->content);

        $this->postRepository->save($post);

        return $post;
    }
}
