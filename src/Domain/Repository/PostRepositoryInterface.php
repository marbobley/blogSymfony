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

    public function findById(int $id): ?Post;

    public function findBySlug(string $slug): ?Post;

    /**
     * @return Post[]
     */
    public function findByTag(\App\Domain\Model\Tag $tag): array;

    public function delete(Post $post): void;
}
