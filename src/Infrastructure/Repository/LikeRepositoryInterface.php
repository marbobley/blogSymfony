<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Infrastructure\Entity\PostLike;

interface LikeRepositoryInterface
{
    public function save(PostLike $entity): void;

    public function delete(PostLike $entity): void;

    public function findOneByPostAndUser(int $postId, int $userId): ?PostLike;

    public function countByPost(int $postId): int;
}
