<?php

declare(strict_types=1);

namespace App\Domain\UseCaseInterface\Like;

interface TogglePostLikeInterface
{
    public function execute(int $postId, int $userId): void;
}
