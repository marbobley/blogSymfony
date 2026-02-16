<?php

declare(strict_types=1);

namespace App\Domain\Provider;

use App\Domain\Model\TagModel;

interface TagProviderInterface
{
    public function save(string $getName): TagModel;

    public function delete(int $id): void;

    public function findById(int $id): TagModel;

    public function findBySlug(string $slug): TagModel;

    /** @return TagModel[] */
    public function findAll(): array;

    public function update(int $id, TagModel $tagDTO): TagModel;
}
