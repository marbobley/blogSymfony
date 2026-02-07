<?php

declare(strict_types=1);

namespace App\Application\UseCase;

use App\Application\Model\PostModel;
use App\Application\UseCaseInterface\CreatePostInterface;
use App\Domain\Model\Post;
use App\Domain\Repository\PostRepositoryInterface;
use App\Domain\Service\PostTagSynchronizer;

class CreatePost implements CreatePostInterface
{
    public function __construct(
        private readonly PostRepositoryInterface $postRepository,
        private readonly PostTagSynchronizer $tagSynchronizer
    ) {
    }

    public function execute(PostModel $postDTO): Post
    {
        $post = new Post($postDTO->getTitle(), $postDTO->getContent());

        $this->tagSynchronizer->synchronize($post, $postDTO);

        $this->postRepository->save($post);

        return $post;
    }
}
