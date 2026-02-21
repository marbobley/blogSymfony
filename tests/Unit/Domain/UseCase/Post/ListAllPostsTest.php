<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\UseCase;

use App\Domain\Criteria\PostCriteria;
use App\Domain\Provider\PostProviderInterface;
use App\Domain\UseCase\Post\ListAllPosts;
use App\Tests\Helper\XmlPostDataTrait;
use Exception;
use PHPUnit\Framework\TestCase;

class ListAllPostsTest extends TestCase
{
    use XmlPostDataTrait;

    /**
     * @throws Exception
     */
    public function testExecuteReturnsAllPosts(): void
    {
        // Arrange
        $posts = $this->loadPostModelsFromXml(__DIR__ . '/../../../Fixtures/posts.xml');
        $postProvider = $this->createMock(PostProviderInterface::class);
        $postProvider->expects($this->once())
            ->method('findByCriteria')
            ->with($this->isInstanceOf(PostCriteria::class))
            ->willReturn($posts);

        $useCase = new ListAllPosts($postProvider);

        // Act
        $result = $useCase->execute();

        // Assert
        $this->assertCount(3, $result);
    }
}
