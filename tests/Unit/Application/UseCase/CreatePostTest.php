<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\UseCase;

use App\Application\Model\PostModel;
use App\Application\Factory\PostModelFactory;
use App\Application\Factory\TagModelFactory;
use App\Application\Provider\PostProviderInterface;
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
        $postProvider = $this->createMock(PostProviderInterface::class);
        $useCase = new CreatePost($postProvider);

        $model = PostModelFactory::create('Titre de test', 'Contenu de test');
        $tagDTO = TagModelFactory::create(1,'Tag test', 'slu');
        $model->addTag($tagDTO);

        // Assert & Expect
        $postProvider->expects($this->once())
            ->method('save')
            ->willReturn($model);

        // Act
        $post = $useCase->execute($model);

        // Additional Assertions
        // TODO WFO : Je tests le mocker ici
        $this->assertEquals('Titre de test', $post->getTitle());
        $this->assertEquals('Contenu de test', $post->getContent());
        $this->assertEquals('Tag test', $post->getTags()[0]->getName());
    }
}
