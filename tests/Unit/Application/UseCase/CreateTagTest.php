<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\UseCase;

use App\Domain\Factory\TagModelFactory;
use App\Domain\Provider\TagProviderInterface;
use App\Domain\UseCase\CreateTag;
use PHPUnit\Framework\TestCase;

class CreateTagTest extends TestCase
{
    public function testExecuteCreatesAndSavesTag(): void
    {
        $tagProvider = $this->createMock(TagProviderInterface::class);
        $useCase = new CreateTag($tagProvider);
        $dto = TagModelFactory::create(1, 'Symfony' , 'slu1');

        $tagProvider->expects($this->once())
            ->method('save')
            ->with('Symfony');

        $tag = $useCase->execute($dto);
    }
}
