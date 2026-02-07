<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\UseCase;

use App\Application\DTO\PostResponseDTO;
use App\Application\UseCase\GetPostBySlug;
use App\Domain\Exception\EntityNotFoundException;
use App\Domain\Model\Post;
use App\Domain\Repository\PostRepositoryInterface;
use PHPUnit\Framework\TestCase;

class GetPostBySlugTest extends TestCase
{
    public function testExecuteReturnsPostResponseDTO(): void
    {
        // Arrange
        $post = new Post('Mon Super Titre', 'Contenu de l\'article');
        // On ne peut pas facilement mocker le slug car il est géré par Gedmo,
        // mais GetPostBySlug utilise findBySlug donc on simule le retour.

        $repository = $this->createMock(PostRepositoryInterface::class);
        $repository->expects($this->once())
            ->method('findBySlug')
            ->with('mon-super-titre')
            ->willReturn($post);

        $useCase = new GetPostBySlug($repository);

        // Act
        $result = $useCase->execute('mon-super-titre');

        // Assert
        $this->assertInstanceOf(PostResponseDTO::class, $result);
        $this->assertEquals('Mon Super Titre', $result->title);
    }

    public function testExecuteThrowsExceptionWhenPostNotFound(): void
    {
        // Arrange
        $repository = $this->createMock(PostRepositoryInterface::class);
        $repository->method('findBySlug')->willReturn(null);

        $useCase = new GetPostBySlug($repository);

        // Assert
        $this->expectException(EntityNotFoundException::class);
        $this->expectExceptionMessage('Post avec l\'identifiant "slug-inexistant" non trouvé(e).');

        // Act
        $useCase->execute('slug-inexistant');
    }
}
