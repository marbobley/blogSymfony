<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\UseCase;

use App\Application\Model\TagModel;
use App\Application\Factory\TagModelFactory;
use App\Application\Provider\TagProviderInterface;
use App\Application\UseCase\UpdateTag;
use App\Domain\Exception\EntityNotFoundException;
use App\Domain\Model\Tag;
use App\Domain\Repository\TagRepositoryInterface;
use PHPUnit\Framework\TestCase;

class UpdateTagTest extends TestCase
{
    public function testExecuteUpdatesAndSavesTag(): void
    {
        $tagProvider = $this->createMock(TagProviderInterface::class);
        $useCase = new UpdateTag($tagProvider);
        $dto = TagModelFactory::create(1,'PHP' , 'Slu1');


        $tagProvider->expects($this->once())
            ->method('update');

        $updatedTag = $useCase->execute(1, $dto);
    }
}
