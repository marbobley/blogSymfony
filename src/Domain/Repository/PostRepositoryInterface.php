<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Infrastructure\Entity\Post;

interface PostRepositoryInterface
{
    /** @param Post $post */
    public function save(object $post): void;

    /**
     * @return Post[]
     */
    public function findAll(): array;

    /** @return Post|null */
    public function findById(int $id): ?object;

    /** @return Post|null */
    public function findBySlug(string $slug): ?object;

    /**
     * @return Post[]
     */
    public function findByTag(\App\Infrastructure\Entity\Tag $tag): array;

    /** @param Post $post */
    public function delete(object $post): void;
}
