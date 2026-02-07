<?php

declare(strict_types=1);

namespace App\Application\UseCase;

use App\Application\DTO\PostDTO;
use App\Application\UseCaseInterface\CreatePostInterface;
use App\Domain\Model\Post;
use App\Domain\Model\Tag;
use App\Domain\Repository\PostRepositoryInterface;

class CreatePost implements CreatePostInterface
{
    public function __construct(
        private readonly PostRepositoryInterface $postRepository
    ) {
    }

    public function execute(PostDTO $postDTO): Post
    {
        $post = new Post($postDTO->getTitle(), $postDTO->getContent());

        foreach ($postDTO->getTags() as $tagDTO) {
            $tag = new Tag($tagDTO->getName());
            $post->addTag($tag);
        }

        $this->postRepository->save($post);

        return $post;
    }
}
