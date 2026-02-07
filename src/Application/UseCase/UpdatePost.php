<?php

declare(strict_types=1);

namespace App\Application\UseCase;

use App\Application\DTO\PostDTO;
use App\Application\UseCaseInterface\UpdatePostInterface;
use App\Domain\Exception\EntityNotFoundException;
use App\Domain\Model\Post;
use App\Domain\Model\Tag;
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
            throw EntityNotFoundException::forEntity('Post', $id);
        }

        $post->setTitle($postDTO->getTitle());
        $post->setContent($postDTO->getContent());

        // Update tags
        // Remove tags not in DTO
        foreach ($post->getTags() as $tag) {
            if (!$postDTO->getTags()->contains($tag)) {
                $post->removeTag($tag);
            }
        }

        // Add tags from DTO
        foreach ($postDTO->getTags() as $tagDTO) {

            $tag = new Tag($tagDTO->getName());

            $post->addTag($tag);
        }

        $this->postRepository->save($post);

        return $post;
    }
}
