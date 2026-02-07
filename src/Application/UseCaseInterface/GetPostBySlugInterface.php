<?php

declare(strict_types=1);

namespace App\Application\UseCaseInterface;

use App\Application\Model\PostModel;

interface GetPostBySlugInterface
{
    public function execute(string $slug): PostModel;
}
