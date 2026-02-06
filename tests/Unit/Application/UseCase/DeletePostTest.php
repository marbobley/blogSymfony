<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\UseCase;

use App\Application\UseCase\DeletePost;
use App\Domain\Model\Post;
use App\Domain\Repository\PostRepositoryInterface;
use PHPUnit\Framework\TestCase;

class DeletePostTest extends TestCase
{
    public function testExecuteDeletesPost(): void
    {
        // Arrange
        $repository = $this->createMock(PostRepositoryInterface::class);
        $post = new Post('Titre', 'Contenu');

        $repository->expects($this->once())
            ->method('findById')
            ->with(1)
            ->willReturn($post);

        $repository->expects($this->once())
            ->method('delete')
            ->with($post);

        $useCase = new DeletePost($repository);

        // Act
        $useCase->execute(1);
    }

    public function testExecuteThrowsExceptionWhenPostNotFound(): void
    {
        // Arrange
        $repository = $this->createMock(PostRepositoryInterface::class);
        $repository->method('findById')->willReturn(null);

        $useCase = new DeletePost($repository);

        // Assert & Expect
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Post not found');

        // Act
        $useCase->execute(1);
    }
}
