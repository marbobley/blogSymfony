<?php

declare(strict_types=1);

namespace App\Application\UseCase;

use App\Application\UseCaseInterface\ListPostsInterface;
use App\Domain\Exception\EntityNotFoundException;
use App\Domain\Repository\PostRepositoryInterface;
use App\Domain\Repository\TagRepositoryInterface;
use App\Infrastructure\MapperInterface\PostMapperInterface;

readonly class ListPosts implements ListPostsInterface
{
    public function __construct(
        private PostRepositoryInterface $postRepository,
        private TagRepositoryInterface $tagRepository,
        private PostMapperInterface $postMapper,
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
            return $this->postMapper->toModel($post);
        }, $posts);
    }
}
