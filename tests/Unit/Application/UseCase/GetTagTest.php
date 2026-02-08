<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\UseCase;

use App\Application\Factory\TagModelFactory;
use App\Application\Model\TagModel;
use App\Application\UseCase\GetTag;
use App\Domain\Exception\EntityNotFoundException;
use App\Domain\Model\Tag;
use App\Domain\Repository\TagRepositoryInterface;
use PHPUnit\Framework\TestCase;

class GetTagTest extends TestCase
{
    public function testExecuteReturnsTagModel(): void
    {
        $repository = $this->createMock(TagRepositoryInterface::class);
        $useCase = new GetTag($repository);

        $tag = new Tag('Symfony');
        $tag->setSlug('Symfony');
        $tag->setId(1);

        $repository->expects($this->once())
            ->method('findById')
            ->with(1)
            ->willReturn($tag);

        $responseDTO = $useCase->execute(1);

        $this->assertEquals('Symfony', $responseDTO->getName());
        $this->assertEquals('Symfony', $responseDTO->getSlug());
    }

    public function testExecuteThrowsExceptionWhenTagNotFound(): void
    {
        $repository = $this->createMock(TagRepositoryInterface::class);
        $useCase = new GetTag($repository);

        $repository->method('findById')->willReturn(null);

        $this->expectException(EntityNotFoundException::class);
        $this->expectExceptionMessage('Tag avec l\'identifiant "1" non trouvé(e).');

        $useCase->execute(1);
    }

    public function testGetByIdReturnsTag(): void
    {
        $repository = $this->createMock(TagRepositoryInterface::class);
        $useCase = new GetTag($repository);
        $tag = new Tag('Symfony');

        $repository->expects($this->once())
            ->method('findById')
            ->with(1)
            ->willReturn($tag);

        $result = $useCase->getById(1);

        $this->assertSame($tag, $result);
    }
}
