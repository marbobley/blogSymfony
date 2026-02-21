<?php

declare(strict_types=1);

namespace App\Domain\UseCaseInterface\Post;

use App\Domain\Model\PostModel;

interface UpdatePostInterface
{
    public function execute(int $id, PostModel $postModel): PostModel;
}
