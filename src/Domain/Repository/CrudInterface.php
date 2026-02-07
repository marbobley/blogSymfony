<?php

namespace App\Domain\Repository;

interface CrudInterface
{
    public function save(Post $post): void;

    /**
     * @return Post[]
     */
    public function findAll(): array;

    public function findById(int $id): ?Post;

    public function findBySlug(string $slug): ?Post;

    public function delete(Post $post): void;

}
