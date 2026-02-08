<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\UseCase;

use App\Application\Factory\PostModelFactory;
use App\Application\Model\PostModel;
use App\Application\Model\PostResponseModel;
use App\Application\Provider\PostProviderInterface;
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
        $post = PostModelFactory::create('Mon Super Titre', 'Contenu de l\'article') ;
           

        $postProvider = $this->createMock(PostProviderInterface::class);
        $postProvider->expects($this->once())
            ->method('findBySlug')
            ->with('mon-super-titre')
            ->willReturn($post);

        $useCase = new GetPostBySlug($postProvider);

        // Act
        $result = $useCase->execute('mon-super-titre');

        // Assert
        $this->assertEquals('Mon Super Titre', $result->getTitle());
    }
}
