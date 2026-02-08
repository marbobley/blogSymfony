<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\UseCase;

use App\Application\Provider\PostProviderInterface;
use App\Application\UseCase\DeletePost;
use App\Infrastructure\Entity\Post;
use PHPUnit\Framework\TestCase;

class DeletePostTest extends TestCase
{
    public function testExecuteDeletesPost(): void
    {
        // Arrange
        $postProvider = $this->createMock(PostProviderInterface::class);
        $post = new Post('Titre', 'Contenu');

        $postProvider->expects($this->once())
            ->method('delete')
            ->with(1);

        $useCase = new DeletePost($postProvider);

        // Act
        $useCase->execute(1);
    }
}
