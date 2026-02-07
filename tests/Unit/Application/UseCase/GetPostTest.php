<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\UseCase;

use App\Application\DTO\PostResponseDTO;
use App\Application\UseCase\GetPost;
use App\Domain\Exception\EntityNotFoundException;
use App\Domain\Model\Post;
use App\Domain\Repository\PostRepositoryInterface;
use PHPUnit\Framework\TestCase;

class GetPostTest extends TestCase
{
    public function testExecuteReturnsPostResponseDTO(): void
    {
        // Arrange
        $post = new Post('Titre', 'Contenu');
        // On ne peut pas facilement mocker l'ID car il est privé et géré par Doctrine,
        // mais GetPost utilise findById donc on simule le retour.

        $repository = $this->createMock(PostRepositoryInterface::class);
        $repository->expects($this->once())
            ->method('findById')
            ->with(1)
            ->willReturn($post);

        $useCase = new GetPost($repository);

        // Act
        $result = $useCase->execute(1);

        // Assert
        $this->assertInstanceOf(PostResponseDTO::class, $result);
        $this->assertEquals('Titre', $result->title);
        $this->assertEquals('Contenu', $result->content);
    }

    public function testExecuteThrowsExceptionWhenPostNotFound(): void
    {
        // Arrange
        $repository = $this->createMock(PostRepositoryInterface::class);
        $repository->method('findById')->willReturn(null);

        $useCase = new GetPost($repository);

        // Assert
        $this->expectException(EntityNotFoundException::class);
        $this->expectExceptionMessage('Post avec l\'identifiant "1" non trouvé(e).');

        // Act
        $useCase->execute(1);
    }
}
