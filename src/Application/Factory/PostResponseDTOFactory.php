<?php

declare(strict_types=1);

namespace App\Application\Factory;

use App\Application\Model\PostResponseModel;

class PostResponseDTOFactory
{
    public static function create(?int $id, string $title, string $slug, string $content, \DateTimeImmutable $createdAt, array $tags = []): PostResponseModel
    {
        return new PostResponseModel($id, $title, $slug, $content, $createdAt, $tags);
    }
}
