<?php

namespace App\Infrastructure\Mapper;

use App\Domain\Model\TagModel;
use App\Infrastructure\MapperInterface\TagMapperInterface;
use App\Infrastructure\Entity\Tag;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class TagMapper implements TagMapperInterface
{

    public function toEntity(TagModel $model): Tag
    {
        return new Tag($model->getName());
    }


    public function toModel(Tag $entity): TagModel
    {
        $tag = new TagModel();
        $tag->setName($entity->getName());
        $tag->setSlug($entity->getSlug());
        $tag->setId((int)$entity->getId());
        return $tag;
    }

    /**
     * @param Collection<int, TagModel> $models
     * @return Collection<int, Tag>
     */
    public function toEntities(Collection $models): Collection
    {
        $entities = new ArrayCollection();
        foreach ($models as $model) {
            $entities->add($this->toEntity($model));
        }

        return $entities;
    }

    /**
     * @param Collection<int, Tag> $entities
     * @return Collection<int, TagModel>
     */
    public function toModels(Collection $entities) : Collection
    {
        $models = new ArrayCollection();
        foreach ($entities as $entity) {
            $models->add($this->toModel($entity));
        }
        return $models;
    }
}
