<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\UseCase;

use App\Application\Factory\TagModelFactory;
use App\Application\Provider\TagProviderInterface;
use App\Application\UseCase\GetTag;
use App\Domain\Model\Tag;
use PHPUnit\Framework\TestCase;

class GetTagTest extends TestCase
{
    public function testExecuteReturnsTagModel(): void
    {
        $tagProvider = $this->createMock(TagProviderInterface::class);
        $useCase = new GetTag($tagProvider);

        $tag = TagModelFactory::create(1 , 'Symfony', 'Symfony' );

        $tagProvider->expects($this->once())
            ->method('findById')
            ->with(1)
            ->willReturn($tag);

        $responseDTO = $useCase->execute(1);

        $this->assertEquals('Symfony', $responseDTO->getName());
        $this->assertEquals('Symfony', $responseDTO->getSlug());
    }
}
