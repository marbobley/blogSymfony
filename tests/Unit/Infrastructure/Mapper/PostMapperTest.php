<?php

declare(strict_types=1);

namespace App\Tests\Unit\Infrastructure\Mapper;

use App\Domain\Model\PostModel;
use App\Infrastructure\Entity\Post;
use App\Infrastructure\Mapper\PostMapper;
use App\Infrastructure\MapperInterface\TagMapperInterface;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use App\Tests\Unit\Helper\TestDataGeneratorTrait;
use PHPUnit\Framework\TestCase;

class PostMapperTest extends TestCase
{
    use TestDataGeneratorTrait;

    private TagMapperInterface $tagMapper;
    private PostMapper $mapper;

    protected function setUp(): void
    {
        $this->tagMapper = $this->createMock(TagMapperInterface::class);
        $this->mapper = new PostMapper($this->tagMapper);
    }

    public function testToEntity(): void
    {
        $postModel = $this->createPostModel(title: 'Test Title', content: 'Test Content');
        $postModel->setSubTitle('subtitle');
        $postModel->publish();
        $tagEntities = new ArrayCollection();
        $this->tagMapper->expects($this->once())
            ->method('toEntities')
            ->willReturn($tagEntities);

        $entity = $this->mapper->toEntity($postModel);

        $this->assertSame('Test Title', $entity->getTitle());
        $this->assertSame('Test Content', $entity->getContent());
        $this->assertSame('subtitle', $entity->getSubTitle());
        $this->assertTrue($entity->isPublished());
        $this->assertSame($tagEntities, $entity->getTags());
    }

    public function testToModel(): void
    {
        $createdAt = new DateTimeImmutable();
        $post = $this->createMock(Post::class);
        $post->method('getTitle')->willReturn('Test Title');
        $post->method('getContent')->willReturn('Test Content');
        $post->method('getId')->willReturn(1);
        $post->method('getSlug')->willReturn('test-title');
        $post->method('getCreatedAt')->willReturn($createdAt);
        $post->method('isPublished')->willReturn(true);
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
    }
}
