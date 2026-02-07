<?php

declare(strict_types=1);

namespace App\Application\Factory;

use App\Application\DTO\PostResponseDTO;

class PostResponseDTOFactory
{
    public static function create(?int $id, string $title, string $slug, string $content, \DateTimeImmutable $createdAt, array $tags = []): PostResponseDTO
    {
        return new PostResponseDTO($id, $title, $slug, $content, $createdAt, $tags);
    }

    public static function createFromEntity(\App\Domain\Model\Post $post): PostResponseDTO
    {
        $tags = [];
        foreach ($post->getTags() as $tag) {
            $tags[$tag->getSlug()] = $tag->getName();
        }

        return self::create(
            $post->getId(),
            $post->getTitle(),
            $post->getSlug() ?? '',
            $post->getContent(),
            $post->getCreatedAt(),
            $tags
        );
    }
}
