<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\UseCase\Post;

use App\Domain\Factory\PostModelBuilder;
use App\Domain\Provider\PostProviderInterface;
use App\Domain\UseCase\Post\UpdatePost;
use PHPUnit\Framework\TestCase;

class UpdatePostTest extends TestCase
{
    public function testExecuteUpdatesAndSavesPost(): void
    {
        $postBuilder = new PostModelBuilder();

        $postModel = $postBuilder
            ->setId(1)
            ->build();
        // Arrange
        $postProvider = $this->createMock(PostProviderInterface::class);
        $useCase = new UpdatePost($postProvider);

        $postProvider->expects($this->once())
            ->method('update');

        // Act
        $useCase->execute($postModel->getId(), $postModel);
    }
}
