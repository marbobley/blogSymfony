<?php

declare(strict_types=1);

namespace App\Application\Factory;

use App\Application\DTO\TagResponseDTO;
use App\Domain\Model\Tag;

class TagResponseDTOFactory
{
    public static function create(?int $id, string $name, string $slug): TagResponseDTO
    {
        return new TagResponseDTO($id, $name, $slug);
    }

    public static function createFromEntity(Tag $tag): TagResponseDTO
    {
        return self::create(
            $tag->getId(),
            $tag->getName(),
            $tag->getSlug()
        );
    }
}
