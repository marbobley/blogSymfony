<?php

declare(strict_types=1);

namespace App\Tests\Unit\Infrastructure\Adapter;

use App\Domain\Exception\EntityNotFoundException;
use App\Domain\Model\PostModel;
use App\Infrastructure\Adapter\PostAdapter;
use App\Infrastructure\Entity\Post;
use App\Infrastructure\Entity\Tag;
use App\Infrastructure\MapperInterface\PostMapperInterface;
use App\Infrastructure\Repository\PostRepositoryInterface;
use App\Infrastructure\Repository\TagRepositoryInterface;
use App\Infrastructure\Service\PostTagSynchronizer;
use PHPUnit\Framework\TestCase;

class PostAdapterTest extends TestCase
{
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
        $postModel = new PostModel();
        $post = new Post('Title', 'Content');
        $post->setSlug('test-slug');

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
        $post = new Post('Title', 'Content');

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
        $post = new Post('Title', 'Content');
        $postModel = new PostModel();

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
        $post = new Post('Title', 'Content');
        $postModel = new PostModel();

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

    public function testFindByTag(): void
    {
        $tagId = 1;
        $tag = new Tag('Test');
        $post = new Post('Title', 'Content');
        $postModel = new PostModel();

        $this->tagRepository->expects($this->once())
            ->method('findById')
            ->with($tagId)
            ->willReturn($tag);

        $this->postRepository->expects($this->once())
            ->method('findByTag')
            ->with($tag)
            ->willReturn([$post]);

        $this->postMapper->expects($this->once())
            ->method('toModel')
            ->with($post)
            ->willReturn($postModel);

        $result = $this->adapter->findByTag($tagId);

        $this->assertCount(1, $result);
        $this->assertSame($postModel, $result[0]);
    }

    public function testFindAllIfTagIdIsNull(): void
    {
        $post = new Post('Title', 'Content');
        $postModel = new PostModel();

        $this->postRepository->expects($this->once())
            ->method('findAll')
            ->willReturn([$post]);

        $this->postMapper->expects($this->once())
            ->method('toModel')
            ->with($post)
            ->willReturn($postModel);

        $result = $this->adapter->findByTag(null);

        $this->assertCount(1, $result);
        $this->assertSame($postModel, $result[0]);
    }

    public function testUpdate(): void
    {
        $id = 1;
        $postModel = new PostModel();
        $postModel->setTitle('New Title');
        $postModel->setContent('New Content');

        $post = $this->createMock(Post::class);

        $this->postRepository->expects($this->once())
            ->method('findById')
            ->with($id)
            ->willReturn($post);

        $post->expects($this->once())
            ->method('setTitle')
            ->with('New Title');
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
