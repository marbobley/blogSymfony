<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\UseCase;

use App\Application\Model\TagModel;
use App\Application\Factory\TagDTOFactory;
use App\Application\UseCase\CreateTag;
use App\Domain\Model\Tag;
use App\Domain\Repository\TagRepositoryInterface;
use PHPUnit\Framework\TestCase;

class CreateTagTest extends TestCase
{
    public function testExecuteCreatesAndSavesTag(): void
    {
        $repository = $this->createMock(TagRepositoryInterface::class);
        $useCase = new CreateTag($repository);
        $dto = TagDTOFactory::create('Symfony');

        $repository->expects($this->once())
            ->method('save')
            ->with($this->isInstanceOf(Tag::class));

        $tag = $useCase->execute($dto);

        $this->assertEquals('Symfony', $tag->getName());
    }
}
