<?php

declare(strict_types=1);

namespace App\Application\UseCaseInterface;

use App\Application\Model\PostResponseModel;

interface GetPostBySlugInterface
{
    public function execute(string $slug): PostResponseModel;
}
