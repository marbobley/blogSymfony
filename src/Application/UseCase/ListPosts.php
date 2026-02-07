<?php

declare(strict_types=1);

namespace App\Application\UseCase;

use App\Application\DTO\PostResponseDTO;
use App\Application\Factory\PostResponseDTOFactory;
use App\Application\UseCaseInterface\ListPostsInterface;
use App\Domain\Exception\EntityNotFoundException;
use App\Domain\Repository\PostRepositoryInterface;

readonly class ListPosts implements ListPostsInterface
{
    public function __construct(
        private PostRepositoryInterface $postRepository,
        private \App\Domain\Repository\TagRepositoryInterface $tagRepository
    ) {
    }

    public function execute(?int $tagId = null): array
    {
        if ($tagId !== null) {
            $tag = $this->tagRepository->findById($tagId);
            if (!$tag) {
                throw EntityNotFoundException::forEntity('Tag', $tagId);
            }
            $posts = $this->postRepository->findByTag($tag);
        } else {
            $posts = $this->postRepository->findAll();
        }

        return array_map(function ($post) {
            return PostResponseDTOFactory::createFromEntity($post);
        }, $posts);
    }
}
