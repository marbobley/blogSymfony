<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\UseCase\Tag;

use App\Domain\Factory\TagModelBuilder;
use App\Domain\Provider\TagProviderInterface;
use App\Domain\UseCase\Tag\UpdateTag;
use PHPUnit\Framework\TestCase;

class UpdateTagTest extends TestCase
{
    public function testExecuteUpdatesAndSavesTag(): void
    {
        $tagProvider = $this->createMock(TagProviderInterface::class);
        $useCase = new UpdateTag($tagProvider);
        $dto = TagModelBuilder::create(1,'PHP' , 'Slu1');


        $tagProvider->expects($this->once())
            ->method('update');

        $updatedTag = $useCase->execute(1, $dto);
    }
}
