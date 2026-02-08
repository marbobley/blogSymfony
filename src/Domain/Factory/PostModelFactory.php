<?php

declare(strict_types=1);

namespace App\Domain\Factory;

use App\Domain\Model\PostModel;

class PostModelFactory
{
    public static function create(string $title = '', string $content = ''): PostModel
    {
        $dto = new PostModel();
        $dto->setTitle($title);
        $dto->setContent($content);

        return $dto;
    }
}
