<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\UseCase;

use App\Application\Model\TagModel;
use App\Application\UseCase\ListTags;
use App\Domain\Model\Tag;
use App\Domain\Repository\TagRepositoryInterface;
use PHPUnit\Framework\TestCase;

class ListTagsTest extends TestCase
{
    public function testExecuteReturnsArrayOfTagModel(): void
    {
        $repository = $this->createMock(TagRepositoryInterface::class);
        $useCase = new ListTags($repository);
        $tag1 = $this->createMock(Tag::class);
        $tag1->method('getName')->willReturn('Symfony');
        $tag1->method('getSlug')->willReturn('symfony');
        $tag2 = $this->createMock(Tag::class);
        $tag2->method('getName')->willReturn('PHP');
        $tag2->method('getSlug')->willReturn('php');

        $repository->expects($this->once())
            ->method('findAll')
            ->willReturn([$tag1, $tag2]);

        $result = $useCase->execute();

        $this->assertCount(2, $result);
        $this->assertInstanceOf(TagModel::class, $result[0]);
        $this->assertEquals('Symfony', $result[0]->name);
        $this->assertEquals('PHP', $result[1]->name);
    }
}
