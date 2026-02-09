<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

interface SeoDataRepositoryInterface
{
    public function findByPageIdentifier(string $identifier);

    public function findAll(): array;

    public function save(object $entity): void;

    public function delete(object $entity): void;
}
