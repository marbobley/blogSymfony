<?php

declare(strict_types=1);

namespace App\Tests\Unit\Infrastructure\Adapter;

use App\Domain\Model\LikeModel;
use App\Infrastructure\Adapter\LikeAdapter;
use App\Infrastructure\Entity\PostLike;
use App\Infrastructure\MapperInterface\LikeMapperInterface;
use App\Infrastructure\Repository\LikeRepositoryInterface;
use PHPUnit\Framework\Attributes\AllowMockObjectsWithoutExpectations;
use PHPUnit\Framework\TestCase;

#[AllowMockObjectsWithoutExpectations]
class LikeAdapterTest extends TestCase
{
    private LikeRepositoryInterface $likeRepository;
    private LikeMapperInterface $likeMapper;
    private LikeAdapter $adapter;

    protected function setUp(): void
    {
        $this->likeRepository = $this->createMock(LikeRepositoryInterface::class);
        $this->likeMapper = $this->createMock(LikeMapperInterface::class);
        $this->adapter = new LikeAdapter($this->likeRepository, $this->likeMapper);
    }

    public function testSave(): void
    {
        $model = new LikeModel();
        $entity = new PostLike();

        $this->likeMapper->expects($this->once())
            ->method('toEntity')
            ->with($model)
            ->willReturn($entity);

        $this->likeRepository->expects($this->once())
            ->method('save')
            ->with($entity);

        $this->adapter->save($model);
    }

    public function testRemove(): void
    {
        $model = new LikeModel();
        $model->setPostId(10);
        $model->setUserId(100);
        $entity = new PostLike();

        $this->likeRepository->expects($this->once())
            ->method('findOneByPostAndUser')
            ->with(10, 100)
            ->willReturn($entity);

        $this->likeRepository->expects($this->once())
            ->method('delete')
            ->with($entity);

        $this->adapter->remove($model);
    }

    public function testRemoveWhenNotFound(): void
    {
        $model = new LikeModel();
        $model->setPostId(10);
        $model->setUserId(100);

        $this->likeRepository->expects($this->once())
            ->method('findOneByPostAndUser')
            ->with(10, 100)
            ->willReturn(null);

        $this->likeRepository->expects($this->never())
            ->method('delete');

        $this->adapter->remove($model);
    }

    public function testFindByPostAndUser(): void
    {
        $entity = new PostLike();
        $model = new LikeModel();

        $this->likeRepository->expects($this->once())
            ->method('findOneByPostAndUser')
            ->with(10, 100)
            ->willReturn($entity);

        $this->likeMapper->expects($this->once())
            ->method('toModel')
            ->with($entity)
            ->willReturn($model);

        $result = $this->adapter->findByPostAndUser(10, 100);

        $this->assertSame($model, $result);
    }

    public function testFindByPostAndUserWhenNotFound(): void
    {
        $this->likeRepository->expects($this->once())
            ->method('findOneByPostAndUser')
            ->with(10, 100)
            ->willReturn(null);

        $this->likeMapper->expects($this->never())
            ->method('toModel');

        $result = $this->adapter->findByPostAndUser(10, 100);

        $this->assertNull($result);
    }

    public function testCountByPost(): void
    {
        $this->likeRepository->expects($this->once())
            ->method('countByPost')
            ->with(10)
            ->willReturn(5);

        $result = $this->adapter->countByPost(10);

        $this->assertEquals(5, $result);
    }
}
