<?php

declare(strict_types=1);

namespace App\Infrastructure\MapperInterface;

use App\Domain\Model\TagModel;
use App\Infrastructure\Entity\Tag;
use Doctrine\Common\Collections\Collection;

interface TagMapperInterface
{
    /**
     * @param Collection<int, Tag> $entities
     * @return Collection<int, TagModel>
     */
    function toModels(Collection $entities): Collection;

    /**
     * @param Collection<int, TagModel> $models
     * @return Collection<int, Tag>
     */
    function toEntities(Collection $models): Collection;

    function toEntity(TagModel $model): Tag;

    function toModel(Tag $entity): TagModel;
}
