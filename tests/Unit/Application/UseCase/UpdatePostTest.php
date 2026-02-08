<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\UseCase;

use App\Application\Model\PostModel;
use App\Application\Factory\PostModelFactory;
use App\Application\Factory\TagModelFactory;
use App\Application\UseCase\UpdatePost;
use App\Domain\Exception\EntityNotFoundException;
use App\Domain\Model\Post;
use App\Domain\Model\Tag;
use App\Domain\Repository\PostRepositoryInterface;
use App\Domain\Repository\TagRepositoryInterface;
use App\Domain\Service\PostTagSynchronizer;
use PHPUnit\Framework\TestCase;

class UpdatePostTest extends TestCase
{
    public function testExecuteUpdatesAndSavesPost(): void
    {
        // Arrange
        $postRepository = $this->createMock(PostRepositoryInterface::class);
        $tagRepository = $this->createMock(TagRepositoryInterface::class);
        $tagSynchronizer = new PostTagSynchronizer($tagRepository);
        $useCase = new UpdatePost($postRepository, $tagSynchronizer);

        $post = new Post('Ancien Titre', 'Ancien Contenu');
        $oldTag = new Tag('Old');
        $post->addTag($oldTag);

        $postRepository->expects($this->once())
            ->method('findById')
            ->with(1)
            ->willReturn($post);

        $postRepository->expects($this->once())
            ->method('save')
            ->with($post);

        $dto = PostModelFactory::create('Nouveau Titre', 'Nouveau Contenu');
        $newTagDTO = TagModelFactory::create(1,'New', 'sllu');
        $dto->addTag($newTagDTO);

        // Act
        $updatedPost = $useCase->execute(1, $dto);

        // Assert
        $this->assertEquals('Nouveau Titre', $updatedPost->getTitle());
        $this->assertEquals('Nouveau Contenu', $updatedPost->getContent());
        $this->assertCount(1, $updatedPost->getTags());
        $this->assertEquals('New', $updatedPost->getTags()->first()->getName());
    }

    public function testExecuteThrowsExceptionWhenPostNotFound(): void
    {
        // Arrange
        $postRepository = $this->createMock(PostRepositoryInterface::class);
        $tagRepository = $this->createMock(TagRepositoryInterface::class);
        $tagSynchronizer = new PostTagSynchronizer($tagRepository);
        $useCase = new UpdatePost($postRepository, $tagSynchronizer);

        $postRepository->method('findById')->willReturn(null);

        $dto = PostModelFactory::create('Titre', 'Contenu');

        // Assert & Expect
        $this->expectException(EntityNotFoundException::class);
        $this->expectExceptionMessage('Post avec l\'identifiant "1" non trouvé(e).');

        // Act
        $useCase->execute(1, $dto);
    }
}
