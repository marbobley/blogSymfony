<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\UseCase;

use App\Application\DTO\PostDTO;
use App\Application\Factory\PostDTOFactory;
use App\Application\Factory\TagDTOFactory;
use App\Application\UseCase\UpdatePost;
use App\Domain\Exception\EntityNotFoundException;
use App\Domain\Model\Post;
use App\Domain\Repository\PostRepositoryInterface;
use phpDocumentor\Reflection\DocBlock\TagFactory;
use PHPUnit\Framework\TestCase;

class UpdatePostTest extends TestCase
{
    public function testExecuteUpdatesAndSavesPost(): void
    {
        // Arrange
        $repository = $this->createMock(PostRepositoryInterface::class);
        $post = new Post('Ancien Titre', 'Ancien Contenu');
        $oldTag = new \App\Domain\Model\Tag('Old');
        $post->addTag($oldTag);

        $repository->expects($this->once())
            ->method('findById')
            ->with(1)
            ->willReturn($post);

        $repository->expects($this->once())
            ->method('save')
            ->with($post);

        $useCase = new UpdatePost($repository);
        $dto = PostDTOFactory::create('Nouveau Titre', 'Nouveau Contenu');
        $newTag = TagDTOFactory::create('New');
        $dto->addTag($newTag);

        // Act
        $updatedPost = $useCase->execute(1, $dto);

        // Assert
        $this->assertEquals('Nouveau Titre', $updatedPost->getTitle());
        $this->assertEquals('Nouveau Contenu', $updatedPost->getContent());

        $this->assertEquals('New', $updatedPost->getTags()->get(1)->getName());

    }

    public function testExecuteThrowsExceptionWhenPostNotFound(): void
    {
        // Arrange
        $repository = $this->createMock(PostRepositoryInterface::class);
        $repository->method('findById')->willReturn(null);

        $useCase = new UpdatePost($repository);
        $dto = PostDTOFactory::create('Titre', 'Contenu');

        // Assert & Expect
        $this->expectException(EntityNotFoundException::class);
        $this->expectExceptionMessage('Post avec l\'identifiant "1" non trouvé(e).');

        // Act
        $useCase->execute(1, $dto);
    }
}
