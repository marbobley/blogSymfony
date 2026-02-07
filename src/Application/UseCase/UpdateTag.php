<?php

declare(strict_types=1);

namespace App\Application\UseCase;

use App\Application\DTO\TagDTO;
use App\Application\UseCaseInterface\UpdateTagInterface;
use App\Domain\Model\Tag;
use App\Domain\Repository\TagRepositoryInterface;

class UpdateTag implements UpdateTagInterface
{
    public function __construct(
        private readonly TagRepositoryInterface $tagRepository
    ) {
    }

    public function execute(int $id, TagDTO $tagDTO): Tag
    {
        $tag = $this->tagRepository->findById($id);

        if (!$tag) {
            throw new \RuntimeException('Tag not found');
        }

        $tag->setName($tagDTO->getName());
        $this->tagRepository->save($tag);

        return $tag;
    }
}
