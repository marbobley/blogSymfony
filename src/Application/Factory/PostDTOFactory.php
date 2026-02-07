<?php

declare(strict_types=1);

namespace App\Application\Factory;

use App\Application\DTO\PostDTO;

class PostDTOFactory
{
    public static function create(string $title = '', string $content = ''): PostDTO
    {
        $dto = new PostDTO();
        $dto->setTitle($title);
        $dto->setContent($content);

        return $dto;
    }
}
