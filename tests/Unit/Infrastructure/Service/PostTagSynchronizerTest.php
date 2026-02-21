<?php

declare(strict_types=1);

namespace App\Tests\Unit\Infrastructure\Service;

use App\Infrastructure\Repository\TagRepositoryInterface;
use App\Infrastructure\Service\PostTagSynchronizer;
use App\Tests\Helper\TestDataGeneratorTrait;
use PHPUnit\Framework\TestCase;

class PostTagSynchronizerTest extends TestCase
{
    use TestDataGeneratorTrait;

    private TagRepositoryInterface $tagRepository;
    private PostTagSynchronizer $synchronizer;

    protected function setUp(): void
    {
        $this->tagRepository = $this->createMock(TagRepositoryInterface::class);
        $this->synchronizer = new PostTagSynchronizer($this->tagRepository);
    }

    public function testSynchronizeAddsNewTags(): void
    {
        $post = $this->createPostEntity();
        $tagModel = $this->createTagModel(name: 'New Tag');
        $postModel = $this->createPostModel(tags: [$tagModel]);

        $this->tagRepository->expects($this->once())
            ->method('findByName')
            ->with('New Tag')
            ->willReturn(null);

        $this->synchronizer->synchronize($post, $postModel);

        $this->assertCount(1, $post->getTags());
        $this->assertSame('New Tag', $post->getTags()->first()->getName());
    }

    public function testSynchronizeUsesExistingTags(): void
    {
        $post = $this->createPostEntity();
        $tagModel = $this->createTagModel(name: 'Existing Tag');
        $postModel = $this->createPostModel(tags: [$tagModel]);

        $existingTag = $this->createTagEntity(name: 'Existing Tag');
        $this->tagRepository->expects($this->once())
            ->method('findByName')
            ->with('Existing Tag')
            ->willReturn($existingTag);

        $this->synchronizer->synchronize($post, $postModel);

        $this->assertCount(1, $post->getTags());
        $this->assertSame($existingTag, $post->getTags()->first());
    }

    public function testSynchronizeRemovesTagsNotInDTO(): void
    {
        $oldTag = $this->createTagEntity(name: 'Old Tag');
        $post = $this->createPostEntity();
        $post->addTag($oldTag);

        $postModel = $this->createPostModel();
        // No tags in model

        $this->synchronizer->synchronize($post, $postModel);

        $this->assertCount(0, $post->getTags());
    }
}
