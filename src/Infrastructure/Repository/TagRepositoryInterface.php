<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Infrastructure\Entity\Tag;

interface TagRepositoryInterface
{
    /** @param Tag $entity */
    public function save(object $entity): void;

    /**
     * @return Tag[]
     */
    public function findAll(): array;

    /** @return Tag|null */
    public function findById(int $id): ?object;

    /** @return Tag|null */
    public function findBySlug(string $slug): ?object;

    /** @return Tag|null */
    public function findByName(string $name): ?object;

    /** @param Tag $entity */
    public function delete(object $entity): void;
}
