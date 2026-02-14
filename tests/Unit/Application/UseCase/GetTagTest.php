<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\UseCase;

use App\Domain\Provider\TagProviderInterface;
use App\Domain\UseCase\GetTag;
use App\Tests\Unit\Helper\XmlTestDataTrait;
use PHPUnit\Framework\TestCase;

class GetTagTest extends TestCase
{
    use XmlTestDataTrait;

    public function testExecuteReturnsTagModel(): void
    {
        $tags = $this->loadTagModelsFromXml(__DIR__ . '/../../../Fixtures/tags.xml');
        $tag = $tags[2]; // Symfony

        $tagProvider = $this->createMock(TagProviderInterface::class);
        $useCase = new GetTag($tagProvider);

        $tagProvider->expects($this->once())
            ->method('findById')
            ->with(3)
            ->willReturn($tag);

        $responseDTO = $useCase->execute(3);

        $this->assertEquals('Symfony', $responseDTO->getName());
        $this->assertEquals('symfony', $responseDTO->getSlug());
    }
}
