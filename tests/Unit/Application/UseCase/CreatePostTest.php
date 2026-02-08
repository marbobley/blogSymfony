<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\UseCase;

use App\Application\Model\PostModel;
use App\Application\Factory\PostDTOFactory;
use App\Application\Factory\TagModelFactory;
use App\Application\UseCase\CreatePost;
use App\Domain\Model\Post;
use App\Domain\Model\Tag;
use App\Domain\Repository\PostRepositoryInterface;
use App\Domain\Repository\TagRepositoryInterface;
use App\Domain\Service\PostTagSynchronizer;
use PHPUnit\Framework\TestCase;

class CreatePostTest extends TestCase
{
    public function testExecuteCreatesAndSavesPost(): void
    {
        // Arrange
        $postRepository = $this->createMock(PostRepositoryInterface::class);
        $tagRepository = $this->createMock(TagRepositoryInterface::class);
        $tagSynchronizer = new PostTagSynchronizer($tagRepository);
        $useCase = new CreatePost($postRepository, $tagSynchronizer);

        $dto = PostDTOFactory::create('Titre de test', 'Contenu de test');
        $tagDTO = TagModelFactory::create(1,'Tag test', 'slu');
        $dto->addTag($tagDTO);

        // Assert & Expect
        $tagRepository->method('findByName')->willReturn(null);
        $postRepository->expects($this->once())
            ->method('save');

        // Act
        $post = $useCase->execute($dto);

        // Additional Assertions
        $this->assertEquals('Titre de test', $post->getTitle());
        $this->assertEquals('Contenu de test', $post->getContent());
        $this->assertEquals('Tag test', $post->getTags()[0]->getName());
        $this->assertInstanceOf(\DateTimeImmutable::class, $post->getCreatedAt());
    }

    public function testExecuteReusesExistingTag(): void
    {
        // Arrange
        $postRepository = $this->createMock(PostRepositoryInterface::class);
        $tagRepository = $this->createMock(TagRepositoryInterface::class);
        $tagSynchronizer = new PostTagSynchronizer($tagRepository);
        $useCase = new CreatePost($postRepository, $tagSynchronizer);

        $existingTag = new Tag('Existing Tag');
        $tagRepository->method('findByName')->with('Existing Tag')->willReturn($existingTag);

        $dto = PostDTOFactory::create('Titre de test', 'Contenu de test');
        $tagDTO = TagModelFactory::create(1,'Existing Tag', 'slug1');
        $dto->addTag($tagDTO);

        // Act
        $post = $useCase->execute($dto);

        // Assert
        $this->assertCount(1, $post->getTags());
        $this->assertSame($existingTag, $post->getTags()[0]);
    }
}
