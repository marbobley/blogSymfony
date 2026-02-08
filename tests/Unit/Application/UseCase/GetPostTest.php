<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\UseCase;

use App\Application\Factory\PostModelFactory;
use App\Application\Model\PostModel;
use App\Application\Provider\PostProviderInterface;
use App\Application\UseCase\GetPost;
use PHPUnit\Framework\TestCase;

class GetPostTest extends TestCase
{
    public function testExecuteReturnsPostResponseDTO(): void
    {
        // Arrange
        $post = PostModelFactory::create('Titre', 'Contenu');

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
        $this->assertEquals('Titre', $result->getTitle());
        $this->assertEquals('Contenu', $result->getContent());
    }
}
