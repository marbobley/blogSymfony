<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Model\Tag;

interface TagRepositoryInterface
{
    /** @param Tag $tag */
    public function save(object $tag): void;

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

    /** @param Tag $tag */
    public function delete(object $tag): void;
}
