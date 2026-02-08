<?php
declare(strict_types=1);

namespace App\Infrastructure\Adapter;

use App\Application\Model\TagModel;
use App\Application\Provider\TagProviderInterface;
use App\Domain\Exception\EntityNotFoundException;
use App\Domain\Exception\TagAlreadyExistsException;
use App\Domain\Model\Tag;
use App\Domain\Repository\TagRepositoryInterface;
use App\Infrastructure\MapperInterface\TagMapperInterface;

readonly class TagAdapter implements TagProviderInterface
{
    public function __construct(
        private TagRepositoryInterface $tagRepository,
        private TagMapperInterface $tagMapper,
    ) {
    }

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

    public function delete(int $id): void
    {
        $tag = $this->tagRepository->findById($id);

        if (!$tag) {
            throw EntityNotFoundException::forEntity('Tag', $id);
        }

        $this->tagRepository->delete($tag);
    }

    public function findById(int $id): TagModel
    {
        $tag = $this->tagRepository->findById($id);

        if (!$tag) {
            throw EntityNotFoundException::forEntity('Tag', $id);
        }

        return $this->tagMapper->toModel($tag);
    }

    public function findBySlug(string $slug): TagModel
    {

        $tag = $this->tagRepository->findBySlug($slug);

        if (!$tag) {
            throw EntityNotFoundException::forEntity('Tag', $slug);
        }

        return $this->tagMapper->toModel($tag);
    }
}
