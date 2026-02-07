<?php

declare(strict_types=1);

namespace App\Application\Factory;

use App\Application\Model\PostModel;

class PostDTOFactory
{
    public static function create(string $title = '', string $content = ''): PostModel
    {
        $dto = new PostModel();
        $dto->setTitle($title);
        $dto->setContent($content);

        return $dto;
    }
}
