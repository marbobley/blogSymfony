<?php

declare(strict_types=1);

namespace App\Application\UseCaseInterface;

use App\Application\Model\PostModel;

interface CreatePostInterface
{
    public function execute(PostModel $postModel): PostModel;
}
