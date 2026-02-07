<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\UseCase;

use App\Application\DTO\PostResponseDTO;
use App\Application\UseCase\ListPosts;
use App\Domain\Exception\EntityNotFoundException;
use App\Domain\Model\Post;
use App\Domain\Repository\PostRepositoryInterface;
use PHPUnit\Framework\TestCase;

class ListPostsTest extends TestCase
{
    public function testExecuteReturnsListOfPostResponseDTO(): void
    {
        // Arrange
        $post1 = new Post('Titre 1', 'Contenu 1');
        $post2 = new Post('Titre 2', 'Contenu 2');

        $repository = $this->createMock(PostRepositoryInterface::class);
        $repository->method('findAll')->willReturn([$post1, $post2]);

        $tagRepository = $this->createMock(\App\Domain\Repository\TagRepositoryInterface::class);

        $useCase = new ListPosts($repository, $tagRepository);

        // Act
        $result = $useCase->execute();

        // Assert
        $this->assertCount(2, $result);
        $this->assertInstanceOf(PostResponseDTO::class, $result[0]);
        $this->assertEquals('Titre 1', $result[0]->title);
        $this->assertEquals('Titre 2', $result[1]->title);
    }

    public function testExecuteWithTagIdReturnsFilteredPosts(): void
    {
        // Arrange
        $tag = $this->createMock(\App\Domain\Model\Tag::class);
        $tag->method('getId')->willReturn(1);
        $post = new Post('Titre Tag', 'Contenu Tag');

        $repository = $this->createMock(PostRepositoryInterface::class);
        $repository->expects($this->once())
            ->method('findByTag')
            ->with($tag)
            ->willReturn([$post]);

        $tagRepository = $this->createMock(\App\Domain\Repository\TagRepositoryInterface::class);
        $tagRepository->expects($this->once())
            ->method('findById')
            ->with(1)
            ->willReturn($tag);

        $useCase = new ListPosts($repository, $tagRepository);

        // Act
        $result = $useCase->execute(1);

        // Assert
        $this->assertCount(1, $result);
        $this->assertEquals('Titre Tag', $result[0]->title);
    }

    public function testExecuteWithNonExistentTagIdThrowsException(): void
    {
        // Arrange
        $repository = $this->createMock(PostRepositoryInterface::class);
        $tagRepository = $this->createMock(\App\Domain\Repository\TagRepositoryInterface::class);
        $tagRepository->method('findById')->willReturn(null);

        $useCase = new ListPosts($repository, $tagRepository);

        // Assert
        $this->expectException(EntityNotFoundException::class);
        $this->expectExceptionMessage('Tag avec l\'identifiant "999" non trouvé(e).');

        // Act
        $useCase->execute(999);
    }
}
