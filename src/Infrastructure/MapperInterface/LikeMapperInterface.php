<?php

declare(strict_types=1);

namespace App\Infrastructure\MapperInterface;

use App\Domain\Model\LikeModel;
use App\Infrastructure\Entity\PostLike;

interface LikeMapperInterface
{
    public function toEntity(LikeModel $model): PostLike;

    public function toModel(PostLike $entity): LikeModel;
}
