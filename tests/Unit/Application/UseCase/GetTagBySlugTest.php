<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\UseCase;

use App\Application\DTO\TagResponseDTO;
use App\Application\UseCase\GetTagBySlug;
use App\Domain\Exception\EntityNotFoundException;
use App\Domain\Model\Tag;
use App\Domain\Repository\TagRepositoryInterface;
use PHPUnit\Framework\TestCase;

class GetTagBySlugTest extends TestCase
{
    public function testExecuteReturnsTagResponseDTO(): void
    {
        $repository = $this->createMock(TagRepositoryInterface::class);
        $useCase = new GetTagBySlug($repository);
        $tag = $this->createMock(Tag::class);
        $tag->method('getName')->willReturn('Symfony');
        $tag->method('getSlug')->willReturn('symfony');

        $repository->expects($this->once())
            ->method('findBySlug')
            ->with('symfony')
            ->willReturn($tag);

        $responseDTO = $useCase->execute('symfony');

        $this->assertInstanceOf(TagResponseDTO::class, $responseDTO);
        $this->assertEquals('Symfony', $responseDTO->name);
        $this->assertEquals('symfony', $responseDTO->slug);
    }

    public function testExecuteThrowsExceptionWhenTagNotFound(): void
    {
        $repository = $this->createMock(TagRepositoryInterface::class);
        $useCase = new GetTagBySlug($repository);

        $repository->method('findBySlug')->willReturn(null);

        $this->expectException(EntityNotFoundException::class);
        $this->expectExceptionMessage('Tag avec l\'identifiant "symfony" non trouvé(e).');

        $useCase->execute('symfony');
    }
}
