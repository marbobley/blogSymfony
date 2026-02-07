<?php

namespace App\Infrastructure\MapperInterface;

use App\Application\Model\PostModel;
use App\Domain\Model\Post;

interface PostMapperInterface
{
    function toEntity(PostModel $postDTO): Post;
    function toModel(Post $post) : PostModel;
}
