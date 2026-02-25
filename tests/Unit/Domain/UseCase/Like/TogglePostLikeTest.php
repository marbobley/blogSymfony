<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\UseCase\Like;

use App\Domain\Model\LikeModel;
use App\Domain\Provider\LikeProviderInterface;
use App\Domain\UseCase\Like\TogglePostLike;
use PHPUnit\Framework\TestCase;

class TogglePostLikeTest extends TestCase
{
    public function testExecuteRemovesLikeWhenAlreadyExists(): void
    {
        $postId = 1;
        $userId = 1;
        $existingLike = new LikeModel();

        $likeProvider = $this->createMock(LikeProviderInterface::class);
        $likeProvider->expects($this->once())
            ->method('findByPostAndUser')
            ->with($postId, $userId)
            ->willReturn($existingLike);

        $likeProvider->expects($this->once())
            ->method('remove')
            ->with($existingLike);

        $likeProvider->expects($this->never())
            ->method('save');

        $useCase = new TogglePostLike($likeProvider);
        $useCase->execute($postId, $userId);
    }

    public function testExecuteSavesLikeWhenDoesNotExist(): void
    {
        $postId = 1;
        $userId = 1;

        $likeProvider = $this->createMock(LikeProviderInterface::class);
        $likeProvider->expects($this->once())
            ->method('findByPostAndUser')
            ->with($postId, $userId)
            ->willReturn(null);

        $likeProvider->expects($this->never())
            ->method('remove');

        $likeProvider->expects($this->once())
            ->method('save')
            ->with($this->callback(function (LikeModel $like) use ($postId, $userId) {
                return $like->getPostId() === $postId && $like->getUserId() === $userId;
            }));

        $useCase = new TogglePostLike($likeProvider);
        $useCase->execute($postId, $userId);
    }
}
