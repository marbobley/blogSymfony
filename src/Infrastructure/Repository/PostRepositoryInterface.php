<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Infrastructure\Entity\Post;

interface PostRepositoryInterface
{
    /** @param Post $post */
    public function save(object $post): void;

    /**
     * @return Post[]
     */
    public function findAll(?string $search = null): array;

    /** @return Post|null */
    public function findById(int $id): ?object;

    /** @return Post|null */
    public function findBySlug(string $slug): ?object;

    /**
     * @return Post[]
     */
    public function findByTag(\App\Infrastructure\Entity\Tag $tag, ?string $search = null): array;

    /**
     * @return Post[]
     */
    public function findPublished(?\App\Infrastructure\Entity\Tag $tag = null, ?string $search = null): array;

    /** @param Post $post */
    public function delete(object $post): void;
}
