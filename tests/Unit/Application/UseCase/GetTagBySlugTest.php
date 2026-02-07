<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\UseCase;

use App\Application\Model\TagModel;
use App\Application\UseCase\GetTagBySlug;
use App\Domain\Exception\EntityNotFoundException;
use App\Domain\Model\Tag;
use App\Domain\Repository\TagRepositoryInterface;
use App\Infrastructure\Mapper\TagMapper;
use PHPUnit\Framework\TestCase;

class GetTagBySlugTest extends TestCase
{

    public function testExecuteReturnsTagModel(): void
    {
        $repository = $this->createMock(TagRepositoryInterface::class);

        $tagMapper = new TagMapper();
        $useCase = new GetTagBySlug($repository,$tagMapper);
        $tag = $this->createMock(Tag::class);
        $tag->method('getName')->willReturn('Symfony');
        $tag->method('getSlug')->willReturn('symfony');

        $repository->expects($this->once())
            ->method('findBySlug')
            ->with('symfony')
            ->willReturn($tag);

        $responseDTO = $useCase->execute('symfony');

        $this->assertInstanceOf(TagModel::class, $responseDTO);
        $this->assertEquals('Symfony', $responseDTO->getName());
        $this->assertEquals('symfony', $responseDTO->getSlug());
    }

    public function testExecuteThrowsExceptionWhenTagNotFound(): void
    {
        $repository = $this->createMock(TagRepositoryInterface::class);
        $tagMapper = new TagMapper();
        $useCase = new GetTagBySlug($repository,$tagMapper);

        $repository->method('findBySlug')->willReturn(null);

        $this->expectException(EntityNotFoundException::class);
        $this->expectExceptionMessage('Tag avec l\'identifiant "symfony" non trouvé(e).');

        $useCase->execute('symfony');
    }
}
