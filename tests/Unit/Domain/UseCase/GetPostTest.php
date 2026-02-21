<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\UseCase;

use App\Domain\Factory\PostModelBuilder;
use App\Domain\Provider\PostProviderInterface;
use App\Domain\UseCase\Post\GetPost;
use PHPUnit\Framework\TestCase;

class GetPostTest extends TestCase
{
    const ID_POST = 1;

    public function testExecuteReturnsPostResponseDTO(): void
    {
        $postBuilder = new PostModelBuilder();
        $postModel = $postBuilder->setId(self::ID_POST)->build();

        $provider = $this->createMock(PostProviderInterface::class);
        $provider->expects($this->once())
            ->method('findById')
            ->with(self::ID_POST)
            ->willReturn($postModel);

        $useCase = new GetPost($provider);

        // Act
        $useCase->execute(self::ID_POST);
    }
}
