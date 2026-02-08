<?php

declare(strict_types=1);

namespace App\Tests\Unit\Infrastructure\Mapper;

use App\Domain\Model\PostModel;
use App\Infrastructure\Entity\Post;
use App\Infrastructure\Mapper\PostMapper;
use App\Infrastructure\MapperInterface\TagMapperInterface;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

class PostMapperTest extends TestCase
{
    private TagMapperInterface $tagMapper;
    private PostMapper $mapper;

    protected function setUp(): void
    {
        $this->tagMapper = $this->createMock(TagMapperInterface::class);
        $this->mapper = new PostMapper($this->tagMapper);
    }

    public function testToEntity(): void
    {
        $postModel = new PostModel();
        $postModel->setTitle('Test Title');
        $postModel->setContent('Test Content');
        $tagModels = new ArrayCollection();
        // Assuming PostModel has getTags and it returns a Collection
        // From PostMapper: $tags = $this->tagMapper->toEntities($postDTO->getTags());

        $tagEntities = new ArrayCollection();
        $this->tagMapper->expects($this->once())
            ->method('toEntities')
            ->willReturn($tagEntities);

        $entity = $this->mapper->toEntity($postModel);

        $this->assertInstanceOf(Post::class, $entity);
        $this->assertSame('Test Title', $entity->getTitle());
        $this->assertSame('Test Content', $entity->getContent());
        $this->assertSame($tagEntities, $entity->getTags());
    }

    public function testToModel(): void
    {
        $post = $this->createMock(Post::class);
        $post->method('getTitle')->willReturn('Test Title');
        $post->method('getContent')->willReturn('Test Content');
        $post->method('getId')->willReturn(1);
        $post->method('getSlug')->willReturn('test-title');
        $createdAt = new \DateTimeImmutable();
        $post->method('getCreatedAt')->willReturn($createdAt);
        $tagEntities = new ArrayCollection();
        $post->method('getTags')->willReturn($tagEntities);

        $tagModels = new ArrayCollection();
        $this->tagMapper->expects($this->once())
            ->method('toModels')
            ->with($tagEntities)
            ->willReturn($tagModels);

        $model = $this->mapper->toModel($post);

        $this->assertInstanceOf(PostModel::class, $model);
        $this->assertSame('Test Title', $model->getTitle());
        $this->assertSame('Test Content', $model->getContent());
        $this->assertSame(1, $model->getId());
        $this->assertSame('test-title', $model->getSlug());
        $this->assertSame($createdAt, $model->getCreatedAt());
        // PostModel adds tags one by one from collection in toModel
        // $tagModels = $this->tagMapper->toModels($post->getTags());
        // $postModel->addTags($tagModels);
    }
}
