<?php

declare(strict_types=1);

namespace App\Tests\Unit\Infrastructure\Adapter;

use App\Domain\Exception\EntityNotFoundException;
use App\Domain\Exception\TagAlreadyExistsException;
use App\Domain\Model\TagModel;
use App\Infrastructure\Adapter\TagAdapter;
use App\Infrastructure\Entity\Tag;
use App\Infrastructure\MapperInterface\TagMapperInterface;
use App\Infrastructure\Repository\TagRepositoryInterface;
use PHPUnit\Framework\TestCase;

class TagAdapterTest extends TestCase
{
    private TagRepositoryInterface $tagRepository;
    private TagMapperInterface $tagMapper;
    private TagAdapter $adapter;

    protected function setUp(): void
    {
        $this->tagRepository = $this->createMock(TagRepositoryInterface::class);
        $this->tagMapper = $this->createMock(TagMapperInterface::class);
        $this->adapter = new TagAdapter($this->tagRepository, $this->tagMapper);
    }

    public function testSave(): void
    {
        $name = 'Test Tag';
        $tag = new Tag($name);
        $tagModel = new TagModel();

        $this->tagRepository->expects($this->once())
            ->method('findByName')
            ->with($name)
            ->willReturn(null);

        $this->tagRepository->expects($this->once())
            ->method('save')
            ->with($this->isInstanceOf(Tag::class));

        $this->tagMapper->expects($this->once())
            ->method('toModel')
            ->willReturn($tagModel);

        $result = $this->adapter->save($name);

        $this->assertSame($tagModel, $result);
    }

    public function testSaveThrowsExceptionIfAlreadyExists(): void
    {
        $name = 'Existing Tag';
        $tag = new Tag($name);

        $this->tagRepository->expects($this->once())
            ->method('findByName')
            ->with($name)
            ->willReturn($tag);

        $this->expectException(TagAlreadyExistsException::class);
        $this->adapter->save($name);
    }

    public function testDelete(): void
    {
        $id = 1;
        $tag = new Tag('Test');

        $this->tagRepository->expects($this->once())
            ->method('findById')
            ->with($id)
            ->willReturn($tag);

        $this->tagRepository->expects($this->once())
            ->method('delete')
            ->with($tag);

        $this->adapter->delete($id);
    }

    public function testFindById(): void
    {
        $id = 1;
        $tag = new Tag('Test');
        $tagModel = new TagModel();

        $this->tagRepository->expects($this->once())
            ->method('findById')
            ->with($id)
            ->willReturn($tag);

        $this->tagMapper->expects($this->once())
            ->method('toModel')
            ->with($tag)
            ->willReturn($tagModel);

        $result = $this->adapter->findById($id);

        $this->assertSame($tagModel, $result);
    }

    public function testFindBySlug(): void
    {
        $slug = 'test-slug';
        $tag = new Tag('Test');
        $tagModel = new TagModel();

        $this->tagRepository->expects($this->once())
            ->method('findBySlug')
            ->with($slug)
            ->willReturn($tag);

        $this->tagMapper->expects($this->once())
            ->method('toModel')
            ->with($tag)
            ->willReturn($tagModel);

        $result = $this->adapter->findBySlug($slug);

        $this->assertSame($tagModel, $result);
    }

    public function testFindAll(): void
    {
        $tag = new Tag('Test');
        $tagModel = new TagModel();

        $this->tagRepository->expects($this->once())
            ->method('findAll')
            ->willReturn([$tag]);

        $this->tagMapper->expects($this->once())
            ->method('toModel')
            ->with($tag)
            ->willReturn($tagModel);

        $result = $this->adapter->findAll();

        $this->assertCount(1, $result);
        $this->assertSame($tagModel, $result[0]);
    }

    public function testUpdate(): void
    {
        $id = 1;
        $tag = $this->createMock(Tag::class);
        $tag->method('getName')->willReturn('Old Name');

        $tagModelInput = new TagModel();
        $tagModelInput->setName('New Name');

        $tagModelOutput = new TagModel();

        $this->tagRepository->expects($this->once())
            ->method('findById')
            ->with($id)
            ->willReturn($tag);

        $this->tagRepository->expects($this->once())
            ->method('findByName')
            ->with('New Name')
            ->willReturn(null);

        $tag->expects($this->once())
            ->method('setName')
            ->with('New Name');

        $this->tagRepository->expects($this->once())
            ->method('save')
            ->with($tag);

        $this->tagMapper->expects($this->once())
            ->method('toModel')
            ->with($tag)
            ->willReturn($tagModelOutput);

        $result = $this->adapter->update($id, $tagModelInput);

        $this->assertSame($tagModelOutput, $result);
    }
}
