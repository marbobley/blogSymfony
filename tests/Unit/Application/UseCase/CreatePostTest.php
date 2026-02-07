<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\UseCase;

use App\Application\DTO\PostDTO;
use App\Application\UseCase\CreatePost;
use App\Domain\Model\Post;
use App\Domain\Repository\PostRepositoryInterface;
use PHPUnit\Framework\TestCase;

class CreatePostTest extends TestCase
{
    public function testExecuteCreatesAndSavesPost(): void
    {
        // Arrange
        $repository = $this->createMock(PostRepositoryInterface::class);
        $useCase = new CreatePost($repository);
        $dto = new PostDTO();
        $dto->setTitle('Titre de test');
        $dto->setContent('Contenu de test');

        // Assert & Expect
        $repository->expects($this->once())
            ->method('save')
            ->with($this->isInstanceOf(Post::class));

        // Act
        $post = $useCase->execute($dto);

        // Additional Assertions
        $this->assertEquals('Titre de test', $post->getTitle());
        $this->assertEquals('Contenu de test', $post->getContent());
        $this->assertInstanceOf(\DateTimeImmutable::class, $post->getCreatedAt());
    }
}
