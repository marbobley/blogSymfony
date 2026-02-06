<?php

namespace App\Application\UseCase;

use App\Application\DTO\PostDTO;
use App\Domain\Model\Post;
use App\Domain\Repository\PostRepositoryInterface;

class CreatePost
{
    public function __construct(
        private PostRepositoryInterface $postRepository
    ) {
    }

    public function execute(PostDTO $postDTO): Post
    {
        $post = new Post($postDTO->title, $postDTO->content);

        $this->postRepository->save($post);

        return $post;
    }
}
