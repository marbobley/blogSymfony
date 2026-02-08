<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\UseCase;

use App\Application\Factory\TagModelFactory;
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
        $tag = new Tag('Symfony');
        $tag->setSlug('Symfony');
        $tag->setId(1);

        $repository->expects($this->once())
            ->method('findBySlug')
            ->with('Symfony')
            ->willReturn($tag);

        $responseDTO = $useCase->execute('Symfony');

        $this->assertInstanceOf(TagModel::class, $responseDTO);
        $this->assertEquals('Symfony', $responseDTO->getName());
        $this->assertEquals('Symfony', $responseDTO->getSlug());
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
