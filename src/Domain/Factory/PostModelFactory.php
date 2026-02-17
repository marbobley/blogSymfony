<?php

declare(strict_types=1);

namespace App\Domain\Factory;

use App\Domain\Model\PostModel;

class PostModelFactory
{
    /**
     * @throws \InvalidArgumentException
     */
    public static function create(string $title = 'Titre par défaut assez long', string $content = ''): PostModel
    {
        $dto = new PostModel();
        if ($title !== '') {
            $dto->setTitle($title);
        }
        $dto->setContent($content);

        return $dto;
    }
}
