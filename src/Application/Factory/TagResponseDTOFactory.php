<?php

declare(strict_types=1);

namespace App\Application\Factory;

use App\Application\Model\TagResponseModel;
use App\Domain\Model\Tag;

class TagResponseDTOFactory
{
    public static function create(?int $id, string $name, string $slug): TagResponseModel
    {
        return new TagResponseModel($id, $name, $slug);
    }

    public static function createFromEntity(Tag $tag): TagResponseModel
    {
        return self::create(
            $tag->getId(),
            $tag->getName(),
            $tag->getSlug()
        );
    }
}
