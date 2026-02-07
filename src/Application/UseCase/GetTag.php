<?php

declare(strict_types=1);

namespace App\Application\UseCase;

use App\Application\DTO\TagResponseDTO;
use App\Application\Factory\TagResponseDTOFactory;
use App\Application\UseCaseInterface\GetTagInterface;
use App\Domain\Repository\TagRepositoryInterface;

class GetTag implements GetTagInterface
{
    public function __construct(
        private readonly TagRepositoryInterface $tagRepository
    ) {
    }

    public function execute(int $id): TagResponseDTO
    {
        $tag = $this->getById($id);

        return TagResponseDTOFactory::createFromEntity($tag);
    }

    public function getById(int $id): \App\Domain\Model\Tag
    {
        $tag = $this->tagRepository->findById($id);

        if (!$tag) {
            throw new \RuntimeException('Tag not found');
        }

        return $tag;
    }
}
