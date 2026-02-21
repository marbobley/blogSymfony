<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\UseCase;

use App\Domain\Provider\PostProviderInterface;
use App\Domain\UseCase\Post\CreatePost;
use App\Tests\Helper\BuilderPostTrait;
use PHPUnit\Framework\TestCase;

class CreatePostTest extends TestCase
{
    use BuilderPostTrait;
    public function testExecuteCreatesAndSavesPost(): void
    {
        $postProvider = $this->createMock(PostProviderInterface::class);
        $useCase = new CreatePost($postProvider);

        $postModel = $this->buildSimplePostModel();

        // Assert & Expect
        $postProvider->expects($this->once())
            ->method('save')
            ->willReturn($postModel);

        $useCase->execute($postModel);
    }
}
