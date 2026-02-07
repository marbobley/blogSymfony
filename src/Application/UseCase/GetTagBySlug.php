<?php

declare(strict_types=1);

namespace App\Application\UseCase;

use App\Application\Model\TagModel;
use App\Application\UseCaseInterface\GetTagBySlugInterface;
use App\Domain\Exception\EntityNotFoundException;
use App\Domain\Repository\TagRepositoryInterface;
use App\Infrastructure\MapperInterface\TagMapperInterface;

class GetTagBySlug implements GetTagBySlugInterface
{
    public function __construct(
        private readonly TagRepositoryInterface $tagRepository,
        private readonly TagMapperInterface $tagMapper
    ) {
    }

    public function execute(string $slug): TagModel
    {
        $tag = $this->tagRepository->findBySlug($slug);

        if (!$tag) {
            throw EntityNotFoundException::forEntity('Tag', $slug);
        }

        return $this->tagMapper->toModel($tag);
    }
}
