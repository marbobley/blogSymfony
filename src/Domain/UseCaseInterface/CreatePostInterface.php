<?php

declare(strict_types=1);

namespace App\Domain\UseCaseInterface;

use App\Domain\Model\PostModel;

interface CreatePostInterface
{
    public function execute(PostModel $postModel): PostModel;
}
