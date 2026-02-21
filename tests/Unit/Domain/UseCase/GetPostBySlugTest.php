<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\UseCase;

use App\Domain\Provider\PostProviderInterface;
use App\Domain\UseCase\GetPostBySlug;
use App\Tests\Helper\XmlPostDataTrait;
use Exception;
use PHPUnit\Framework\TestCase;

class GetPostBySlugTest extends TestCase
{
    use XmlPostDataTrait;

    /**
     * @throws Exception
     */
    public function testExecuteReturnsPostResponseDTO(): void
    {
        // Arrange
        $post = $this->loadPostModelsFromXml(__DIR__ . '/../../../Fixtures/posts.xml')[0];

        $postProvider = $this->createMock(PostProviderInterface::class);
        $postProvider->expects($this->once())
            ->method('findBySlug')
            ->with('decouvrir-l-architecture-hexagonale')
            ->willReturn($post);

        $useCase = new GetPostBySlug($postProvider);

        // Act
        $result = $useCase->execute('decouvrir-l-architecture-hexagonale');

        // Assert
        $this->assertEquals('Découvrir l\'Architecture Hexagonale', $result->getTitle());
    }
}
