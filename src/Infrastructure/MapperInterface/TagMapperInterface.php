<?php

namespace App\Infrastructure\MapperInterface;

use App\Domain\Model\TagModel;
use App\Infrastructure\Entity\Tag;
use Doctrine\Common\Collections\Collection;

interface TagMapperInterface
{

    function toModels(Collection $entities): Collection;
    function toEntities(Collection $models): Collection;
    function toEntity(TagModel $model): Tag;
    function toModel(Tag $entity) : TagModel;
}
