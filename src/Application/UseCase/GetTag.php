<?php

declare(strict_types=1);

namespace App\Application\UseCase;

use App\Application\Model\TagModel;
use App\Application\Factory\TagResponseDTOFactory;
use App\Application\UseCaseInterface\GetTagInterface;
use App\Domain\Exception\EntityNotFoundException;
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

        return TagResponseDTOFactory::createFromEntity($tag);
    }

    public function getById(int $id): \App\Domain\Model\Tag
    {
        $tag = $this->tagRepository->findById($id);

        if (!$tag) {
            throw EntityNotFoundException::forEntity('Tag', $id);
        }

        return $tag;
    }
}
