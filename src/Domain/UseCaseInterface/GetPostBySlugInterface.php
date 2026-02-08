<?php

declare(strict_types=1);

namespace App\Domain\UseCaseInterface;

use App\Domain\Model\PostModel;

interface GetPostBySlugInterface
{
    public function execute(string $slug): PostModel;
}
