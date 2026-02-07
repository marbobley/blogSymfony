<?php

declare(strict_types=1);

namespace App\Application\UseCase;

use App\Application\Model\TagModel;
use App\Application\UseCaseInterface\UpdateTagInterface;
use App\Domain\Exception\EntityNotFoundException;
use App\Domain\Exception\TagAlreadyExistsException;
use App\Domain\Model\Tag;
use App\Domain\Repository\TagRepositoryInterface;

class UpdateTag implements UpdateTagInterface
{
    public function __construct(
        private readonly TagRepositoryInterface $tagRepository
    ) {
    }

    public function execute(int $id, TagModel $tagDTO): Tag
    {
        /** @var Tag|null $tag */
        $tag = $this->tagRepository->findById($id);

        if (!$tag) {
            throw EntityNotFoundException::forEntity('Tag', $id);
        }

        $newName = $tagDTO->getName();

        if ($tag->getName() !== $newName) {
            $existingTag = $this->tagRepository->findByName($newName);
            if ($existingTag instanceof Tag) {
                throw new TagAlreadyExistsException($newName);
            }
        }

        $tag->setName($newName);
        $this->tagRepository->save($tag);

        return $tag;
    }
}
