<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\UseCase;

use App\Application\Model\PostModel;
use App\Application\Model\PostResponseModel;
use App\Application\UseCase\GetPostBySlug;
use App\Domain\Exception\EntityNotFoundException;
use App\Domain\Model\Post;
use App\Domain\Repository\PostRepositoryInterface;
use App\Infrastructure\Mapper\PostMapper;
use App\Infrastructure\Mapper\TagMapper;
use PHPUnit\Framework\TestCase;

class GetPostBySlugTest extends TestCase
{
    public function testExecuteReturnsPostResponseDTO(): void
    {
        // Arrange
        $post = new Post('Mon Super Titre', 'Contenu de l\'article');
        $post->setId(1);
        $post->setSlug('slu');

        $repository = $this->createMock(PostRepositoryInterface::class);
        $repository->expects($this->once())
            ->method('findBySlug')
            ->with('mon-super-titre')
            ->willReturn($post);

        $tagMapper = new TagMapper();
        $postMappeer = new PostMapper($tagMapper);

        $useCase = new GetPostBySlug($repository, $postMappeer);

        // Act
        $result = $useCase->execute('mon-super-titre');

        // Assert
        $this->assertEquals('Mon Super Titre', $result->getTitle());
    }

    public function testExecuteThrowsExceptionWhenPostNotFound(): void
    {
        // Arrange
        $repository = $this->createMock(PostRepositoryInterface::class);
        $repository->method('findBySlug')->willReturn(null);

        $tagMapper = new TagMapper();
        $postMappeer = new PostMapper($tagMapper);
        $useCase = new GetPostBySlug($repository,$postMappeer);

        // Assert
        $this->expectException(EntityNotFoundException::class);
        $this->expectExceptionMessage('Post avec l\'identifiant "slug-inexistant" non trouvé(e).');

        // Act
        $useCase->execute('slug-inexistant');
    }
}
