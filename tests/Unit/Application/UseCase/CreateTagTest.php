<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\UseCase;

use App\Application\Model\TagModel;
use App\Application\Factory\TagModelFactory;
use App\Application\Provider\TagProviderInterface;
use App\Application\UseCase\CreateTag;
use App\Domain\Model\Tag;
use App\Domain\Repository\TagRepositoryInterface;
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
