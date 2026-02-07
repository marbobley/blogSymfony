<?php

declare(strict_types=1);

namespace App\Application\UseCase;

use App\Application\Model\PostModel;
use App\Application\UseCaseInterface\UpdatePostInterface;
use App\Domain\Exception\EntityNotFoundException;
use App\Domain\Model\Post;
use App\Domain\Repository\PostRepositoryInterface;
use App\Domain\Service\PostTagSynchronizer;

class UpdatePost implements UpdatePostInterface
{
    public function __construct(
        private readonly PostRepositoryInterface $postRepository,
        private readonly PostTagSynchronizer $tagSynchronizer
    ) {
    }

    public function execute(int $id, PostModel $postDTO): Post
    {
        $post = $this->postRepository->findById($id);

        if (!$post) {
            throw EntityNotFoundException::forEntity('Post', $id);
        }

        $post->setTitle($postDTO->getTitle());
        $post->setContent($postDTO->getContent());

        $this->tagSynchronizer->synchronize($post, $postDTO);

        $this->postRepository->save($post);

        return $post;
    }
}
