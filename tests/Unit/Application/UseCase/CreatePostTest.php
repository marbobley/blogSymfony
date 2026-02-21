<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\UseCase;

use App\Domain\Factory\PostModelBuilder;
use App\Domain\Factory\TagModelFactory;
use App\Domain\Provider\PostProviderInterface;
use App\Domain\UseCase\CreatePost;
use PHPUnit\Framework\TestCase;

class CreatePostTest extends TestCase
{
    public function testExecuteCreatesAndSavesPost(): void
    {
        $postBuilder = new PostModelBuilder();
        $postModel = $postBuilder->setId(1)->build();
        // Arrange
        $postProvider = $this->createMock(PostProviderInterface::class);
        $useCase = new CreatePost($postProvider);


        // Assert & Expect
        $postProvider->expects($this->once())
            ->method('save')
            ->willReturn($postModel);

        $useCase->execute($postModel);
    }
}
