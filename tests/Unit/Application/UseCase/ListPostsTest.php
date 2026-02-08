<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\UseCase;

use App\Application\Factory\PostModelFactory;
use App\Application\Model\PostModel;
use App\Application\Provider\PostProviderInterface;
use App\Application\UseCase\ListPosts;
use PHPUnit\Framework\TestCase;

class ListPostsTest extends TestCase
{
    public function testExecuteReturnsListOfPostResponseDTO(): void
    {
        // Arrange
        $post1 = PostModelFactory::create('Titre 1', 'Contenu 1');
        $post2 = PostModelFactory::create('Titre 2', 'Contenu 2');

        $postProvider = $this->createMock(PostProviderInterface::class);
        $postProvider->method('findByTag')->willReturn([$post1, $post2]);


        $useCase = new ListPosts($postProvider);

        // Act
        $result = $useCase->execute();

        // Assert
        $this->assertCount(2, $result);
        $this->assertInstanceOf(PostModel::class, $result[0]);
        $this->assertEquals('Titre 1', $result[0]->getTitle());
        $this->assertEquals('Titre 2', $result[1]->getTitle());
    }
}
