<?php

namespace App\Infrastructure\MapperInterface;

use App\Domain\Model\PostModel;
use App\Infrastructure\Entity\Post;

interface PostMapperInterface
{
    function toEntity(PostModel $postDTO): Post;
    function toModel(Post $post) : PostModel;
}
