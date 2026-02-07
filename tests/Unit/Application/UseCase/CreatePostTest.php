<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\UseCase;

use App\Application\DTO\PostDTO;
use App\Application\Factory\PostDTOFactory;
use App\Application\Factory\TagDTOFactory;
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
        $tag = TagDTOFactory::create('Tag test');
        $dto->addTag($tag);

        // Assert & Expect
        $repository->expects($this->once())
            ->method('save');

        // Act
        $post = $useCase->execute($dto);

        // Additional Assertions
        $this->assertEquals('Titre de test', $post->getTitle());
        $this->assertEquals('Contenu de test', $post->getContent());
        $this->assertEquals('Tag test', $post->getTags()[0]->getName());
        $this->assertInstanceOf(\DateTimeImmutable::class, $post->getCreatedAt());
    }
}
