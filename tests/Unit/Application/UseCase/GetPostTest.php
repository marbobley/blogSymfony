<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\UseCase;

use App\Application\Model\PostModel;
use App\Application\Model\PostResponseModel;
use App\Application\UseCase\GetPost;
use App\Domain\Exception\EntityNotFoundException;
use App\Domain\Model\Post;
use App\Domain\Repository\PostRepositoryInterface;
use App\Infrastructure\Mapper\PostMapper;
use App\Infrastructure\Mapper\TagMapper;
use PHPUnit\Framework\TestCase;

class GetPostTest extends TestCase
{
    public function testExecuteReturnsPostResponseDTO(): void
    {
        // Arrange
        $post = new Post('Titre', 'Contenu');
        $post->setId(33);

        $repository = $this->createMock(PostRepositoryInterface::class);
        $repository->expects($this->once())
            ->method('findById')
            ->with(1)
            ->willReturn($post);

        $tagMapper = new TagMapper();
        $mapper = new PostMapper($tagMapper);

        $useCase = new GetPost($repository, $mapper);

        // Act
        $result = $useCase->execute(1);

        // Assert
        $this->assertInstanceOf(PostModel::class, $result);
        $this->assertEquals('Titre', $result->getTitle());
        $this->assertEquals('Contenu', $result->getContent());
    }

    public function testExecuteThrowsExceptionWhenPostNotFound(): void
    {
        // Arrange
        $repository = $this->createMock(PostRepositoryInterface::class);
        $repository->method('findById')->willReturn(null);

        $tagMapper = new TagMapper();
        $mapper = new PostMapper($tagMapper);

        $useCase = new GetPost($repository, $mapper);

        // Assert
        $this->expectException(EntityNotFoundException::class);
        $this->expectExceptionMessage('Post avec l\'identifiant "1" non trouvé(e).');

        // Act
        $useCase->execute(1);
    }
}
