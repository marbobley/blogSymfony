<?php

namespace App\Infrastructure\MapperInterface;

use App\Domain\Model\SeoModel;
use App\Domain\Model\TagModel;
use App\Infrastructure\Entity\SeoData;
use App\Infrastructure\Entity\Tag;
use Doctrine\Common\Collections\Collection;

interface SeoDataMapperInterface
{
    public function toEntity(SeoModel $model): SeoData;
    public function toModel(SeoData $entity): SeoModel;
    public function updateEntity(SeoData $entity, SeoModel $model): void;
}
