<?php

declare(strict_types=1);

namespace App\Tests\Unit\Infrastructure\Mapper;

use App\Domain\Model\TagModel;
use App\Infrastructure\Entity\Tag;
use App\Infrastructure\Mapper\TagMapper;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

class TagMapperTest extends TestCase
{
    private TagMapper $mapper;

    protected function setUp(): void
    {
        $this->mapper = new TagMapper();
    }

    public function testToEntity(): void
    {
        $model = new TagModel();
        $model->setName('Test Tag');

        $entity = $this->mapper->toEntity($model);

        $this->assertInstanceOf(Tag::class, $entity);
        $this->assertSame('Test Tag', $entity->getName());
    }

    public function testToModel(): void
    {
        $entity = $this->createMock(Tag::class);
        $entity->method('getName')->willReturn('Test Tag');
        $entity->method('getSlug')->willReturn('test-tag');
        $entity->method('getId')->willReturn(123);

        $model = $this->mapper->toModel($entity);

        $this->assertInstanceOf(TagModel::class, $model);
        $this->assertSame('Test Tag', $model->getName());
        $this->assertSame('test-tag', $model->getSlug());
        $this->assertSame(123, $model->getId());
    }

    public function testToEntities(): void
    {
        $model = new TagModel();
        $model->setName('Tag 1');
        $models = new ArrayCollection([$model]);

        $entities = $this->mapper->toEntities($models);

        $this->assertCount(1, $entities);
        $this->assertInstanceOf(Tag::class, $entities->first());
        $this->assertSame('Tag 1', $entities->first()->getName());
    }

    public function testToModels(): void
    {
        $entity = $this->createMock(Tag::class);
        $entity->method('getName')->willReturn('Tag 1');
        $entity->method('getId')->willReturn(1);
        $entities = new ArrayCollection([$entity]);

        $models = $this->mapper->toModels($entities);

        $this->assertCount(1, $models);
        $this->assertInstanceOf(TagModel::class, $models->first());
        $this->assertSame('Tag 1', $models->first()->getName());
    }
}
