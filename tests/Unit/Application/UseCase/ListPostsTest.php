<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\UseCase;

use App\Domain\Factory\PostModelFactory;
use App\Domain\Model\PostModel;
use App\Domain\Provider\PostProviderInterface;
use App\Domain\UseCase\ListPosts;
use PHPUnit\Framework\TestCase;

class ListPostsTest extends TestCase
{
    public function testExecuteReturnsListOfPostResponseDTO(): void
    {
        // Arrange
        $post1 = PostModelFactory::create('Titre 1 assez long', 'Contenu 1');
        $post2 = PostModelFactory::create('Titre 2 assez long', 'Contenu 2');

        $postProvider = $this->createMock(PostProviderInterface::class);
        $postProvider->method('findPublished')->willReturn([$post1, $post2]);


        $useCase = new ListPosts($postProvider);

        // Act
        $result = $useCase->execute();

        // Assert
        $this->assertCount(2, $result);
        $this->assertInstanceOf(PostModel::class, $result[0]);
        $this->assertEquals('Titre 1 assez long', $result[0]->getTitle());
        $this->assertEquals('Titre 2 assez long', $result[1]->getTitle());
    }
}
