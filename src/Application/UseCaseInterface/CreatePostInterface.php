<?php

declare(strict_types=1);

namespace App\Application\UseCaseInterface;

use App\Application\DTO\PostDTO;
use App\Domain\Model\Post;

interface CreatePostInterface
{
    public function execute(PostDTO $postDTO): Post;
}
