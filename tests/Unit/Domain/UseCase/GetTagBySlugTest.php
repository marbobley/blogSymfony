<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\UseCase;

use App\Domain\Model\TagModel;
use App\Domain\Provider\TagProviderInterface;
use App\Domain\UseCase\GetTagBySlug;
use App\Tests\Helper\XmlTestDataTrait;
use PHPUnit\Framework\TestCase;

class GetTagBySlugTest extends TestCase
{
    use XmlTestDataTrait;

    public function testExecuteReturnsTagModel(): void
    {
        $tags = $this->loadTagModelsFromXml(__DIR__ . '/../../../Fixtures/tags.xml');
        $tag = $tags[2]; // Symfony

        $tagProvider = $this->createMock(TagProviderInterface::class);

        $useCase = new GetTagBySlug($tagProvider);

        $tagProvider->expects($this->once())
            ->method('findBySlug')
            ->with('symfony')
            ->willReturn($tag);

        $responseDTO = $useCase->execute('symfony');

        $this->assertInstanceOf(TagModel::class, $responseDTO);
        $this->assertEquals('Symfony', $responseDTO->getName());
        $this->assertEquals('symfony', $responseDTO->getSlug());
    }
}
