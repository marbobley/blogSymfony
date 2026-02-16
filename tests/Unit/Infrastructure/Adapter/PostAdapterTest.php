<?php

declare(strict_types=1);

namespace App\Tests\Unit\Infrastructure\Adapter;

use App\Domain\Criteria\PostCriteria;
use App\Domain\Exception\EntityNotFoundException;
use App\Domain\Model\PostModel;
use App\Infrastructure\Adapter\PostAdapter;
use App\Infrastructure\Entity\Post;
use App\Infrastructure\Entity\Tag;
use App\Infrastructure\MapperInterface\PostMapperInterface;
use App\Infrastructure\Repository\PostRepositoryInterface;
use App\Infrastructure\Repository\TagRepositoryInterface;
use App\Infrastructure\Service\PostTagSynchronizer;
use App\Tests\Unit\Helper\TestDataGeneratorTrait;
use PHPUnit\Framework\TestCase;

class PostAdapterTest extends TestCase
{
    use TestDataGeneratorTrait;

    private PostRepositoryInterface $postRepository;
    private TagRepositoryInterface $tagRepository;
    private PostMapperInterface $postMapper;
    private PostTagSynchronizer $postTagSynchronizer;
    private PostAdapter $adapter;

    protected function setUp(): void
    {
        $this->postRepository = $this->createMock(PostRepositoryInterface::class);
        $this->tagRepository = $this->createMock(TagRepositoryInterface::class);
        $this->postMapper = $this->createMock(PostMapperInterface::class);
        $this->postTagSynchronizer = $this->createMock(PostTagSynchronizer::class);

        $this->adapter = new PostAdapter(
            $this->postRepository,
            $this->tagRepository,
            $this->postMapper,
            $this->postTagSynchronizer
        );
    }

    public function testSave(): void
    {
        $postModel = $this->createPostModel();
        $post = $this->createPostEntity(slug: 'test-slug');

        $this->postMapper->expects($this->once())
            ->method('toEntity')
            ->with($postModel)
            ->willReturn($post);

        $this->postTagSynchronizer->expects($this->once())
            ->method('synchronize')
            ->with($post, $postModel);

        $this->postRepository->expects($this->once())
            ->method('save')
            ->with($post);

        $this->postRepository->expects($this->once())
            ->method('findBySlug')
            ->with('test-slug')
            ->willReturn($post);

        $this->postMapper->expects($this->once())
            ->method('toModel')
            ->with($post)
            ->willReturn($postModel);

        $result = $this->adapter->save($postModel);

        $this->assertSame($postModel, $result);
    }

    public function testDelete(): void
    {
        $id = 1;
        $post = $this->createPostEntity();

        $this->postRepository->expects($this->once())
            ->method('findById')
            ->with($id)
            ->willReturn($post);

        $this->postRepository->expects($this->once())
            ->method('delete')
            ->with($post);

        $this->adapter->delete($id);
    }

    public function testDeleteThrowsExceptionIfNotFound(): void
    {
        $id = 1;

        $this->postRepository->expects($this->once())
            ->method('findById')
            ->with($id)
            ->willReturn(null);

        $this->expectException(EntityNotFoundException::class);
        $this->adapter->delete($id);
    }

    public function testFindById(): void
    {
        $id = 1;
        $post = $this->createPostEntity();
        $postModel = $this->createPostModel();

        $this->postRepository->expects($this->once())
            ->method('findById')
            ->with($id)
            ->willReturn($post);

        $this->postMapper->expects($this->once())
            ->method('toModel')
            ->with($post)
            ->willReturn($postModel);

        $result = $this->adapter->findById($id);

        $this->assertSame($postModel, $result);
    }

    public function testFindBySlug(): void
    {
        $slug = 'test-slug';
        $post = $this->createPostEntity(slug: $slug);
        $postModel = $this->createPostModel();

        $this->postRepository->expects($this->once())
            ->method('findBySlug')
            ->with($slug)
            ->willReturn($post);

        $this->postMapper->expects($this->once())
            ->method('toModel')
            ->with($post)
            ->willReturn($postModel);

        $result = $this->adapter->findBySlug($slug);

        $this->assertSame($postModel, $result);
    }

    public function testFindByCriteria(): void
    {
        $tagId = 1;
        $post = $this->createPostEntity();
        $postModel = $this->createPostModel();
        $criteria = new PostCriteria(tagId: $tagId);

        $this->postRepository->expects($this->once())
            ->method('findByCriteria')
            ->with($criteria)
            ->willReturn([$post]);

        $this->postMapper->expects($this->once())
            ->method('toModel')
            ->with($post)
            ->willReturn($postModel);

        $result = $this->adapter->findByCriteria($criteria);

        $this->assertCount(1, $result);
        $this->assertSame($postModel, $result[0]);
    }

    public function testFindAllByCriteria(): void
    {
        $post = $this->createPostEntity();
        $postModel = $this->createPostModel();
        $criteria = new PostCriteria();

        $this->postRepository->expects($this->once())
            ->method('findByCriteria')
            ->with($criteria)
            ->willReturn([$post]);

        $this->postMapper->expects($this->once())
            ->method('toModel')
            ->with($post)
            ->willReturn($postModel);

        $result = $this->adapter->findByCriteria($criteria);

        $this->assertCount(1, $result);
        $this->assertSame($postModel, $result[0]);
    }

    public function testUpdate(): void
    {
        $id = 1;
        $postModel = $this->createPostModel(title: 'New Title Long Enough', content: 'New Content');
        $postModel->setSubTitle('subtitle');

        $post = $this->createMock(Post::class);

        $this->postRepository->expects($this->once())
            ->method('findById')
            ->with($id)
            ->willReturn($post);

        $post->expects($this->once())
            ->method('setTitle')
            ->with('New Title Long Enough');
        $post->expects($this->once())
            ->method('setContent')
            ->with('New Content');

        $this->postTagSynchronizer->expects($this->once())
            ->method('synchronize')
            ->with($post, $postModel);

        $this->postRepository->expects($this->once())
            ->method('save')
            ->with($post);

        $this->postMapper->expects($this->once())
            ->method('toModel')
            ->with($post)
            ->willReturn($postModel);

        $result = $this->adapter->update($id, $postModel);

        $this->assertSame($postModel, $result);
    }
}
