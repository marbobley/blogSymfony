<?php

declare(strict_types=1);

namespace App\Tests\Unit\Infrastructure\Mapper;

use App\Domain\Model\LikeModel;
use App\Infrastructure\Entity\Post;
use App\Infrastructure\Entity\PostLike;
use App\Infrastructure\Entity\User;
use App\Infrastructure\Mapper\LikeMapper;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\Attributes\AllowMockObjectsWithoutExpectations;
use PHPUnit\Framework\TestCase;

#[AllowMockObjectsWithoutExpectations]
class LikeMapperTest extends TestCase
{
    private EntityManagerInterface $entityManager;
    private LikeMapper $mapper;

    protected function setUp(): void
    {
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->mapper = new LikeMapper($this->entityManager);
    }

    public function testToEntity(): void
    {
        $createdAt = new DateTimeImmutable();
        $model = new LikeModel();
        $model->setPostId(10);
        $model->setUserId(100);
        $model->setCreatedAt($createdAt);

        $post = $this->createMock(Post::class);
        $user = $this->createMock(User::class);

        $this->entityManager->expects($this->exactly(2))
            ->method('getReference')
            ->willReturnMap([
                [Post::class, 10, $post],
                [User::class, 100, $user],
            ]);

        $entity = $this->mapper->toEntity($model);

        $this->assertInstanceOf(PostLike::class, $entity);
        $this->assertSame($post, $entity->getPost());
        $this->assertSame($user, $entity->getUser());
        $this->assertSame($createdAt, $entity->getCreatedAt());
    }

    public function testToModel(): void
    {
        $createdAt = new DateTimeImmutable();

        $post = $this->createMock(Post::class);
        $post->method('getId')->willReturn(10);

        $user = $this->createMock(User::class);
        $user->method('getId')->willReturn(100);

        $entity = new PostLike();
        $entity->setPost($post);
        $entity->setUser($user);
        $entity->setCreatedAt($createdAt);

        // Simuler l'ID via réflexion car PostLike n'a pas de setId
        $reflection = new \ReflectionClass($entity);
        $property = $reflection->getProperty('id');
        $property->setAccessible(true);
        $property->setValue($entity, 1);

        $model = $this->mapper->toModel($entity);

        $this->assertInstanceOf(LikeModel::class, $model);
        $this->assertEquals(1, $model->getId());
        $this->assertEquals(10, $model->getPostId());
        $this->assertEquals(100, $model->getUserId());
        $this->assertSame($createdAt, $model->getCreatedAt());
    }
}
