<?php

declare(strict_types=1);

namespace App\Application\Factory;

use App\Application\DTO\TagDTO;
use App\Domain\Model\Tag;

class TagDTOFactory
{
    public static function create(string $name = ''): TagDTO
    {
        $dto = new TagDTO();
        $dto->setName($name);

        return $dto;
    }

    public static function createFromEntity(Tag $tag): TagDTO
    {
        return self::create($tag->getName());
    }
}
