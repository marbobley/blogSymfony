<?php

namespace App\Tests\Unit\Application\UseCase;

use App\Application\DTO\PostResponseDTO;
use App\Application\UseCase\ListPosts;
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

        $useCase = new ListPosts($repository);

        // Act
        $result = $useCase->execute();

        // Assert
        $this->assertIsArray($result);
        $this->assertCount(2, $result);
        $this->assertInstanceOf(PostResponseDTO::class, $result[0]);
        $this->assertEquals('Titre 1', $result[0]->title);
        $this->assertEquals('Titre 2', $result[1]->title);
    }
}
