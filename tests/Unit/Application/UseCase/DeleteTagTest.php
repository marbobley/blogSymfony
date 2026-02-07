<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\UseCase;

use App\Application\UseCase\DeleteTag;
use App\Domain\Exception\EntityNotFoundException;
use App\Domain\Model\Tag;
use App\Domain\Repository\TagRepositoryInterface;
use PHPUnit\Framework\TestCase;

class DeleteTagTest extends TestCase
{
    public function testExecuteDeletesTag(): void
    {
        $repository = $this->createMock(TagRepositoryInterface::class);
        $useCase = new DeleteTag($repository);
        $tag = new Tag('Symfony');

        $repository->expects($this->once())
            ->method('findById')
            ->with(1)
            ->willReturn($tag);

        $repository->expects($this->once())
            ->method('delete')
            ->with($tag);

        $useCase->execute(1);
    }

    public function testExecuteThrowsExceptionWhenTagNotFound(): void
    {
        $repository = $this->createMock(TagRepositoryInterface::class);
        $useCase = new DeleteTag($repository);

        $repository->method('findById')->willReturn(null);

        $this->expectException(EntityNotFoundException::class);
        $this->expectExceptionMessage('Tag avec l\'identifiant "1" non trouvé(e).');

        $useCase->execute(1);
    }
}
