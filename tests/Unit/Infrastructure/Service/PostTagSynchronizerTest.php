<?php

declare(strict_types=1);

namespace App\Tests\Unit\Infrastructure\Service;

use App\Domain\Model\PostModel;
use App\Domain\Model\TagModel;
use App\Infrastructure\Entity\Post;
use App\Infrastructure\Entity\Tag;
use App\Infrastructure\Repository\TagRepositoryInterface;
use App\Infrastructure\Service\PostTagSynchronizer;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

class PostTagSynchronizerTest extends TestCase
{
    private TagRepositoryInterface $tagRepository;
    private PostTagSynchronizer $synchronizer;

    protected function setUp(): void
    {
        $this->tagRepository = $this->createMock(TagRepositoryInterface::class);
        $this->synchronizer = new PostTagSynchronizer($this->tagRepository);
    }

    public function testSynchronizeAddsNewTags(): void
    {
        $post = new Post('Title', 'Content');
        $postModel = new PostModel();

        $tagModel = new TagModel();
        $tagModel->setName('New Tag');
        $postModel->addTags(new ArrayCollection([$tagModel]));

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
        $post = new Post('Title', 'Content');
        $postModel = new PostModel();

        $tagModel = new TagModel();
        $tagModel->setName('Existing Tag');
        $postModel->addTags(new ArrayCollection([$tagModel]));

        $existingTag = new Tag('Existing Tag');
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
        $oldTag = new Tag('Old Tag');
        $post = new Post('Title', 'Content');
        $post->addTag($oldTag);

        $postModel = new PostModel();
        // No tags in model

        $this->synchronizer->synchronize($post, $postModel);

        $this->assertCount(0, $post->getTags());
    }
}
