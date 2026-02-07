<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\UseCase;

use App\Application\DTO\PostDTO;
use App\Application\Factory\PostDTOFactory;
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
        $dto = PostDTOFactory::create('Titre de test', 'Contenu de test');
        $tag = new \App\Domain\Model\Tag('Symfony');
        $dto->addTag($tag);

        // Assert & Expect
        $repository->expects($this->once())
            ->method('save')
            ->with($this->callback(function (Post $post) use ($tag) {
                return $post->getTitle() === 'Titre de test' && $post->getTags()->contains($tag);
            }));

        // Act
        $post = $useCase->execute($dto);

        // Additional Assertions
        $this->assertEquals('Titre de test', $post->getTitle());
        $this->assertEquals('Contenu de test', $post->getContent());
        $this->assertTrue($post->getTags()->contains($tag));
        $this->assertInstanceOf(\DateTimeImmutable::class, $post->getCreatedAt());
    }
}
