<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Model\Tag;

interface TagRepositoryInterface
{
    public function save(Tag $tag): void;

    /**
     * @return Tag[]
     */
    public function findAll(): array;

    public function findById(int $id): ?Tag;

    public function findBySlug(string $slug): ?Tag;

    public function delete(Tag $tag): void;
}
