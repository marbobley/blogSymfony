<?php

declare(strict_types=1);

namespace App\Application\UseCaseInterface;

use App\Application\Model\PostModel;
use App\Domain\Model\Post;

interface CreatePostInterface
{
    public function execute(PostModel $postModel): PostModel;
}
