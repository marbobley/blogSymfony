<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\UseCase;

use App\Domain\Model\PostModel;
use App\Domain\Provider\PostProviderInterface;
use App\Domain\UseCase\ListPosts;
use App\Tests\Unit\Helper\XmlTestDataTrait;
use PHPUnit\Framework\TestCase;

class ListPostsTest extends TestCase
{
    use XmlTestDataTrait;

    public function testExecuteReturnsListOfPostResponseDTO(): void
    {
        // Arrange
        $posts = $this->loadPostModelsFromXml(__DIR__ . '/../../../Fixtures/posts.xml');
        $publishedPosts = array_filter($posts, fn($p) => $p->isPublished());

        $postProvider = $this->createMock(PostProviderInterface::class);
        $postProvider->expects($this->once())
            ->method('findPublished')
            ->with(null, null)
            ->willReturn($publishedPosts);


        $useCase = new ListPosts($postProvider);

        // Act
        $result = $useCase->execute();

        // Assert
        $this->assertCount(2, $result);
        $this->assertInstanceOf(PostModel::class, $result[0]);
        $this->assertEquals('Découvrir l\'Architecture Hexagonale', $result[0]->getTitle());
        $this->assertEquals('Les nouveautés de Symfony 7.4', $result[1]->getTitle());
    }

    public function testExecuteReturnsAllPostsWhenOnlyPublishedIsFalse(): void
    {
        // Arrange
        $posts = $this->loadPostModelsFromXml(__DIR__ . '/../../../Fixtures/posts.xml');
        $postProvider = $this->createMock(PostProviderInterface::class);
        $postProvider->expects($this->once())
            ->method('findByTag')
            ->with(null, null)
            ->willReturn($posts);

        $useCase = new ListPosts($postProvider);

        // Act
        $result = $useCase->execute(onlyPublished: false);

        // Assert
        $this->assertCount(3, $result);
    }
}
