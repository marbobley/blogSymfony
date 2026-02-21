<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\UseCase;

use App\Domain\Provider\PostProviderInterface;
use App\Domain\UseCase\Post\DeletePost;
use PHPUnit\Framework\TestCase;

class DeletePostTest extends TestCase
{
    public function testExecuteDeletesPost(): void
    {
        // Arrange
        $postProvider = $this->createMock(PostProviderInterface::class);

        $postProvider->expects($this->once())
            ->method('delete')
            ->with(1);

        $useCase = new DeletePost($postProvider);

        // Act
        $useCase->execute(1);
    }
}
