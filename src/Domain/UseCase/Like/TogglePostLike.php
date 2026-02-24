<?php

declare(strict_types=1);

namespace App\Domain\UseCase\Like;

use App\Domain\Model\LikeModel;
use App\Domain\Provider\LikeProviderInterface;
use App\Domain\UseCaseInterface\Like\TogglePostLikeInterface;

class TogglePostLike implements TogglePostLikeInterface
{
    public function __construct(
        private readonly LikeProviderInterface $likeProvider,
    ) {}

    public function execute(int $postId, int $userId): void
    {
        $like = $this->likeProvider->findByPostAndUser($postId, $userId);

        if ($like) {
            $this->likeProvider->remove($like);
            return;
        }

        $newLike = new LikeModel();
        $newLike->setPostId($postId);
        $newLike->setUserId($userId);

        $this->likeProvider->save($newLike);
    }
}
