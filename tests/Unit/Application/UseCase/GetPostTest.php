<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\UseCase;

use App\Domain\Factory\PostModelFactory;
use App\Domain\Model\PostModel;
use App\Domain\Provider\PostProviderInterface;
use App\Domain\UseCase\GetPost;
use PHPUnit\Framework\TestCase;

class GetPostTest extends TestCase
{
    public function testExecuteReturnsPostResponseDTO(): void
    {
        // Arrange
        $post = PostModelFactory::create('Titre assez long pour passer', 'Contenu');

        $provider = $this->createMock(PostProviderInterface::class);
        $provider->expects($this->once())
            ->method('findById')
            ->with(1)
            ->willReturn($post);


        $useCase = new GetPost($provider);

        // Act
        $result = $useCase->execute(1);

        // Assert
        // TODO WFO : on test le mocker
        $this->assertInstanceOf(PostModel::class, $result);
        $this->assertEquals('Titre assez long pour passer', $result->getTitle());
        $this->assertEquals('Contenu', $result->getContent());
    }
}
