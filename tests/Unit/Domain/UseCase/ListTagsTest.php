<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\UseCase;

use App\Domain\Factory\TagModelFactory;
use App\Domain\Model\TagModel;
use App\Domain\Provider\TagProviderInterface;
use App\Domain\UseCase\ListTags;
use PHPUnit\Framework\TestCase;

class ListTagsTest extends TestCase
{
    public function testExecuteReturnsArrayOfTagModel(): void
    {
        $tagProvider = $this->createMock(TagProviderInterface::class);
        $useCase = new ListTags($tagProvider);
        $tag1 = TagModelFactory::create(1, 'Symfony' , 'slu1');
        $tag2 = TagModelFactory::create(2, 'PHP' , 'slu2');

        $tagProvider->expects($this->once())
            ->method('findAll')
            ->willReturn([$tag1, $tag2]);

        $result = $useCase->execute();

        $this->assertCount(2, $result);
        $this->assertInstanceOf(TagModel::class, $result[0]);
        $this->assertEquals('Symfony', $result[0]->getName());
        $this->assertEquals('PHP', $result[1]->getName());
    }
}
