<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Model\Post;

interface PostRepositoryInterface
{
    public function save(Post $post): void;

    /**
     * @return Post[]
     */
    public function findAll(): array;
}
