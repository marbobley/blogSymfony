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

    public static function createFromEntity(\App\Domain\Model\Post $post): PostDTO
    {
        $dto = self::create($post->getTitle(), $post->getContent());
        foreach ($post->getTags() as $tag) {
            $dto->addTag($tag);
        }

        return $dto;
    }
}
