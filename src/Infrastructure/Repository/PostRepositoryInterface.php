<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Infrastructure\Entity\Post;

interface PostRepositoryInterface
{
    /** @param Post $entity */
    public function save(object $entity): void;

    /** @return Post|null */
    public function findById(int $id): ?object;

    /** @return Post|null */
    public function findBySlug(string $slug): ?object;

    /**
     * @return Post[]
     */
    public function findByCriteria(\App\Domain\Criteria\PostCriteria $criteria): array;

    /** @param Post $entity */
    public function delete(object $entity): void;
}
