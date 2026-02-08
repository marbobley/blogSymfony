<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\UseCase;

use App\Application\Model\PostModel;
use App\Application\UseCase\ListPosts;
use App\Domain\Exception\EntityNotFoundException;
use App\Domain\Model\Post;
use App\Domain\Repository\PostRepositoryInterface;
use App\Infrastructure\Mapper\PostMapper;
use App\Infrastructure\Mapper\TagMapper;
use PHPUnit\Framework\TestCase;

class ListPostsTest extends TestCase
{
    public function testExecuteReturnsListOfPostResponseDTO(): void
    {
        // Arrange
        $post1 = new Post('Titre 1', 'Contenu 1');
        $post1->setId(1);
        $post1->setSlug('skl');
        $post2 = new Post('Titre 2', 'Contenu 2');
        $post2->setId(2);
        $post2->setSlug('skl');

        $repository = $this->createMock(PostRepositoryInterface::class);
        $repository->method('findAll')->willReturn([$post1, $post2]);

        $tagRepository = $this->createMock(\App\Domain\Repository\TagRepositoryInterface::class);

        $mapper = new TagMapper();
        $mapperPost = new PostMapper($mapper);

        $useCase = new ListPosts($repository, $tagRepository, $mapperPost);

        // Act
        $result = $useCase->execute();

        // Assert
        $this->assertCount(2, $result);
        $this->assertInstanceOf(PostModel::class, $result[0]);
        $this->assertEquals('Titre 1', $result[0]->getTitle());
        $this->assertEquals('Titre 2', $result[1]->getTitle());
    }

    public function testExecuteWithTagIdReturnsFilteredPosts(): void
    {
        // Arrange
        $tag = $this->createMock(\App\Domain\Model\Tag::class);
        $tag->method('getId')->willReturn(1);
        $post = new Post('Titre Tag', 'Contenu Tag');
        $post->setId(1);
        $post->setSlug('skl');

        $repository = $this->createMock(PostRepositoryInterface::class);
        $repository->expects($this->once())
            ->method('findByTag')
            ->with($tag)
            ->willReturn([$post]);

        $tagRepository = $this->createMock(\App\Domain\Repository\TagRepositoryInterface::class);
        $tagRepository->expects($this->once())
            ->method('findById')
            ->with(1)
            ->willReturn($tag);

        $mapper = new TagMapper();
        $mapperPost = new PostMapper($mapper);
        $useCase = new ListPosts($repository, $tagRepository, $mapperPost);

        // Act
        $result = $useCase->execute(1);

        // Assert
        $this->assertCount(1, $result);
        $this->assertEquals('Titre Tag', $result[0]->getTitle());
    }

    public function testExecuteWithNonExistentTagIdThrowsException(): void
    {
        // Arrange
        $repository = $this->createMock(PostRepositoryInterface::class);
        $tagRepository = $this->createMock(\App\Domain\Repository\TagRepositoryInterface::class);
        $tagRepository->method('findById')->willReturn(null);

        $mapper = new TagMapper();
        $mapperPost = new PostMapper($mapper);
        $useCase = new ListPosts($repository, $tagRepository,$mapperPost);

        // Assert
        $this->expectException(EntityNotFoundException::class);
        $this->expectExceptionMessage('Tag avec l\'identifiant "999" non trouvé(e).');

        // Act
        $useCase->execute(999);
    }
}
