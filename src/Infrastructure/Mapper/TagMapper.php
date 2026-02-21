<?php

declare(strict_types=1);

namespace App\Infrastructure\Mapper;

use App\Domain\Factory\TagModelFactory;
use App\Domain\Model\TagModel;
use App\Infrastructure\Entity\Tag;
use App\Infrastructure\MapperInterface\TagMapperInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class TagMapper implements TagMapperInterface
{
    public function toEntity(TagModel $model): Tag
    {
        return new Tag($model->getName());
    }

    /**
     * @throws \InvalidArgumentException
     */
    public function toModel(Tag $entity): TagModel
    {
        $tagBuilder = new TagModelFactory();

        return $tagBuilder
            ->setId($entity->getId())
            ->setName($entity->getName())
            ->setSlug($entity->getSlug())
            ->build();
    }

    /**
     * @param Collection<int, TagModel> $models
     * @return Collection<int, Tag>
     * @throws \InvalidArgumentException
     */
    public function toEntities(Collection $models): Collection
    {
        /** @var Collection<int, Tag> $entities */
        $entities = new ArrayCollection();
        foreach ($models as $model) {
            $entities->add($this->toEntity($model));
        }

        return $entities;
    }

    /**
     * @param Collection<int, Tag> $entities
     * @return Collection<int, TagModel>
     * @throws \InvalidArgumentException
     */
    public function toModels(Collection $entities): Collection
    {
        /** @var Collection<int, TagModel> $models */
        $models = new ArrayCollection();
        foreach ($entities as $entity) {
            $models->add($this->toModel($entity));
        }
        return $models;
    }
}
