<?php

declare(strict_types=1);

namespace App\Application\UseCase;

use App\Application\Factory\TagModelFactory;
use App\Application\Model\TagModel;
use App\Application\UseCaseInterface\GetTagInterface;
use App\Domain\Exception\EntityNotFoundException;
use App\Domain\Model\Tag;
use App\Domain\Repository\TagRepositoryInterface;

class GetTag implements GetTagInterface
{
    public function __construct(
        private readonly TagRepositoryInterface $tagRepository
    ) {
    }

    public function execute(int $id): TagModel
    {
        $tag = $this->getById($id);

        return TagModelFactory::create($tag->getId(), $tag->getName(), $tag->getSlug());
    }

    public function getById(int $id): Tag
    {
        $tag = $this->tagRepository->findById($id);

        if (!$tag) {
            throw EntityNotFoundException::forEntity('Tag', $id);
        }

        return $tag;
    }
}
