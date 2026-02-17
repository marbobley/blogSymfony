<?php

declare(strict_types=1);

namespace App\Infrastructure\Adapter;

use App\Domain\Exception\EntityNotFoundException;
use App\Domain\Exception\TagAlreadyExistsException;
use App\Domain\Model\TagModel;
use App\Domain\Provider\TagProviderInterface;
use App\Infrastructure\Entity\Tag;
use App\Infrastructure\MapperInterface\TagMapperInterface;
use App\Infrastructure\Repository\TagRepositoryInterface;

readonly class TagAdapter implements TagProviderInterface
{
    public function __construct(
        private TagRepositoryInterface $tagRepository,
        private TagMapperInterface $tagMapper,
    ) {}

    /**
     * @throws TagAlreadyExistsException
     */
    public function save(string $getName): TagModel
    {
        $existingTag = $this->tagRepository->findByName($getName);
        if ($existingTag instanceof Tag) {
            throw new TagAlreadyExistsException($getName);
        }

        $tag = new Tag($getName);
        $this->tagRepository->save($tag);

        return $this->tagMapper->toModel($tag);
    }

    /**
     * @throws EntityNotFoundException
     */
    public function delete(int $id): void
    {
        $tag = $this->tagRepository->findById($id);

        if (!$tag) {
            throw EntityNotFoundException::forEntity('Tag', $id);
        }

        $this->tagRepository->delete($tag);
    }

    /**
     * @throws EntityNotFoundException
     */
    public function findById(int $id): TagModel
    {
        $tag = $this->tagRepository->findById($id);

        if (!$tag) {
            throw EntityNotFoundException::forEntity('Tag', $id);
        }

        return $this->tagMapper->toModel($tag);
    }

    /**
     * @throws EntityNotFoundException
     */
    public function findBySlug(string $slug): TagModel
    {
        $tag = $this->tagRepository->findBySlug($slug);

        if (!$tag) {
            throw EntityNotFoundException::forEntity('Tag', $slug);
        }

        return $this->tagMapper->toModel($tag);
    }

    /**
     * @return TagModel[]
     */
    public function findAll(): array
    {
        $tags = $this->tagRepository->findAll();

        return array_map(fn($tag) => $this->tagMapper->toModel($tag), $tags);
    }

    /**
     * @throws EntityNotFoundException
     * @throws TagAlreadyExistsException
     */
    public function update(int $id, TagModel $tagDTO): TagModel
    {
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

        return $this->tagMapper->toModel($tag);
    }
}
