<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\UseCase;

use App\Domain\Model\PostModel;
use App\Domain\Provider\PostProviderInterface;
use App\Domain\UseCase\ListAllPosts;
use App\Tests\Unit\Helper\XmlTestDataTrait;
use PHPUnit\Framework\TestCase;

class ListAllPostsTest extends TestCase
{
    use XmlTestDataTrait;

    public function testExecuteReturnsAllPosts(): void
    {
        // Arrange
        $posts = $this->loadPostModelsFromXml(__DIR__ . '/../../../Fixtures/posts.xml');
        $postProvider = $this->createMock(PostProviderInterface::class);
        $postProvider->expects($this->once())
            ->method('findByTag')
            ->with(null, null)
            ->willReturn($posts);

        $useCase = new ListAllPosts($postProvider);

        // Act
        $result = $useCase->execute();

        // Assert
        $this->assertCount(3, $result);
    }
}
