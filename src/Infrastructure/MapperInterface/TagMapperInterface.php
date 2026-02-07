<?php

namespace App\Infrastructure\MapperInterface;

use App\Application\Model\TagModel;
use Doctrine\Common\Collections\Collection;
use App\Domain\Model\Tag;

interface TagMapperInterface
{

    function toModels(Collection $entities): Collection;
    function toEntities(Collection $models): Collection;
    function toEntity(TagModel $model): Tag;
    function toModel(Tag $entity) : TagModel;
}
