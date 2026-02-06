<?php

namespace App\Application\UseCaseInterface;

use App\Application\DTO\PostDTO;
use App\Domain\Model\Post;

interface CreatePostInterface
{
    public function execute(PostDTO $postDTO): Post;
}
