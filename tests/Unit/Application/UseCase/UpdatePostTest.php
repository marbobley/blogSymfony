<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\UseCase;

use App\Application\Factory\PostModelFactory;
use App\Application\Factory\TagModelFactory;
use App\Application\Provider\PostProviderInterface;
use App\Application\UseCase\UpdatePost;
use PHPUnit\Framework\TestCase;

class UpdatePostTest extends TestCase
{
    public function testExecuteUpdatesAndSavesPost(): void
    {
        // TODO WFO TEST A REVOIR
        // Arrange
        $postProvider = $this->createMock(PostProviderInterface::class);
        $useCase = new UpdatePost($postProvider);

        $post = PostModelFactory::create('Ancien Titre', 'Ancien Contenu');
        $oldTag = TagModelFactory::create(1,  'Old', 'Old');
        $post->addTag($oldTag);

        $postProvider->expects($this->once())
            ->method('update');

        $dto = PostModelFactory::create('Nouveau Titre', 'Nouveau Contenu');
        $newTagDTO = TagModelFactory::create(1,'New', 'sllu');
        $dto->addTag($newTagDTO);

        // Act
        $updatedPost = $useCase->execute(1, $dto);
    }
}
