<?php

declare(strict_types=1);

namespace App\Application\Factory;

use App\Application\DTO\PostResponseDTO;

class PostResponseDTOFactory
{
    public static function create(?int $id, string $title, string $slug, string $content, \DateTimeImmutable $createdAt): PostResponseDTO
    {
        return new PostResponseDTO($id, $title, $slug, $content, $createdAt);
    }

    public static function createFromEntity(\App\Domain\Model\Post $post): PostResponseDTO
    {
        return self::create(
            $post->getId(),
            $post->getTitle(),
            $post->getSlug() ?? '',
            $post->getContent(),
            $post->getCreatedAt()
        );
    }
}
