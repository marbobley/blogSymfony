<?php

declare(strict_types=1);

namespace App\Application\Factory;

use App\Application\Model\TagModel;
use App\Domain\Model\Tag;

class TagDTOFactory
{
    public static function create(string $name = ''): TagModel
    {
        $dto = new TagModel();
        $dto->setName($name);

        return $dto;
    }
}
