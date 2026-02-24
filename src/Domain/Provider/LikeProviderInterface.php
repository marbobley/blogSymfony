<?php

declare(strict_types=1);

namespace App\Domain\Provider;

use App\Domain\Model\LikeModel;

interface LikeProviderInterface
{
    public function save(LikeModel $like): void;

    public function remove(LikeModel $like): void;

    public function findByPostAndUser(int $postId, int $userId): ?LikeModel;

    public function countByPost(int $postId): int;
}
