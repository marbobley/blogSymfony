<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\UseCase;

use App\Application\Model\TagModel;
use App\Application\Factory\TagDTOFactory;
use App\Application\UseCase\UpdateTag;
use App\Domain\Exception\EntityNotFoundException;
use App\Domain\Model\Tag;
use App\Domain\Repository\TagRepositoryInterface;
use PHPUnit\Framework\TestCase;

class UpdateTagTest extends TestCase
{
    public function testExecuteUpdatesAndSavesTag(): void
    {
        $repository = $this->createMock(TagRepositoryInterface::class);
        $useCase = new UpdateTag($repository);
        $tag = new Tag('Symfony');
        $dto = TagDTOFactory::create('PHP');

        $repository->expects($this->once())
            ->method('findById')
            ->with(1)
            ->willReturn($tag);

        $repository->expects($this->once())
            ->method('save')
            ->with($tag);

        $updatedTag = $useCase->execute(1, $dto);

        $this->assertEquals('PHP', $updatedTag->getName());
    }

    public function testExecuteThrowsExceptionWhenTagNotFound(): void
    {
        $repository = $this->createMock(TagRepositoryInterface::class);
        $useCase = new UpdateTag($repository);
        $dto = TagDTOFactory::create('PHP');

        $repository->method('findById')->willReturn(null);

        $this->expectException(EntityNotFoundException::class);
        $this->expectExceptionMessage('Tag avec l\'identifiant "1" non trouvé(e).');

        $useCase->execute(1, $dto);
    }
}
