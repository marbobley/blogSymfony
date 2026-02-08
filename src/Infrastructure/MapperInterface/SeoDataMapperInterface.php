<?php

namespace App\Infrastructure\MapperInterface;

use App\Domain\Model\SeoModel;
use App\Domain\Model\TagModel;
use App\Infrastructure\Entity\SeoData;
use App\Infrastructure\Entity\Tag;
use Doctrine\Common\Collections\Collection;

interface SeoDataMapperInterface
{

    function toModels(Collection $entities): Collection;
    function toEntities(Collection $models): Collection;
    function toEntity(SeoModel $model): SeoData;
    function toModel(SeoData $entity) : SeoModel;
}
