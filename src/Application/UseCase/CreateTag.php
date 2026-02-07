<?php

declare(strict_types=1);

namespace App\Application\UseCase;

use App\Application\Model\TagModel;
use App\Application\UseCaseInterface\CreateTagInterface;
use App\Domain\Exception\TagAlreadyExistsException;
use App\Domain\Model\Tag;
use App\Domain\Repository\TagRepositoryInterface;

class CreateTag implements CreateTagInterface
{
    public function __construct(
        private readonly TagRepositoryInterface $tagRepository
    ) {
    }

    public function execute(TagModel $tagDTO): Tag
    {
        $existingTag = $this->tagRepository->findByName($tagDTO->getName());
        if ($existingTag instanceof Tag) {
            throw new TagAlreadyExistsException($tagDTO->getName());
        }

        $tag = new Tag($tagDTO->getName());
        $this->tagRepository->save($tag);

        return $tag;
    }
}
