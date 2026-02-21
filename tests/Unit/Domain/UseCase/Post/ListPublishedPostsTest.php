<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\UseCase\Post;

use App\Domain\Criteria\PostCriteria;
use App\Domain\Model\PostModel;
use App\Domain\Provider\PostProviderInterface;
use App\Domain\UseCase\Post\ListPublishedPosts;
use App\Tests\Helper\XmlPostDataTrait;
use Exception;
use PHPUnit\Framework\TestCase;

class ListPublishedPostsTest extends TestCase
{
    use XmlPostDataTrait;

    /**
     * @throws Exception
     */
    public function testExecuteReturnsPublishedPosts(): void
    {
        // Arrange
        $posts = $this->loadPostModelsFromXml();
        $publishedPosts = array_filter($posts, fn($p) => $p->isPublished());

        $postProvider = $this->createMock(PostProviderInterface::class);
        $postProvider->expects($this->once())
            ->method('findByCriteria')
            ->with($this->callback(fn(PostCriteria $c) => $c->isOnlyPublished() === true))
            ->willReturn($publishedPosts);

        $useCase = new ListPublishedPosts($postProvider);

        // Act
        $result = $useCase->execute();

        // Assert
        $this->assertCount(2, $result);
        $this->assertInstanceOf(PostModel::class, $result[0]);
        $this->assertEquals('Découvrir l\'Architecture Hexagonale', $result[0]->getTitle());
        $this->assertEquals('Les nouveautés de Symfony 7.4', $result[1]->getTitle());
    }
}
