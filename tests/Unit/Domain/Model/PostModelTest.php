<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Model;

use App\Domain\Model\PostModel;
use App\Domain\Model\TagModel;
use PHPUnit\Framework\TestCase;
use DateTimeImmutable;

class PostModelTest extends TestCase
{
    public function testPostModelAccessors(): void
    {
        $post = new PostModel();

        $post->setId(1);
        $this->assertEquals(1, $post->getId());

        $post->setTitle('Titre de test assez long');
        $this->assertEquals('Titre de test assez long', $post->getTitle());

        $post->setSubTitle('Sous-titre');
        $this->assertEquals('Sous-titre', $post->getSubTitle());

        $post->setContent('Contenu de test');
        $this->assertEquals('Contenu de test', $post->getContent());

        $post->setSlug('titre-de-test');
        $this->assertEquals('titre-de-test', $post->getSlug());

        $now = new DateTimeImmutable();
        $post->setCreatedAt($now);
        $this->assertEquals($now, $post->getCreatedAt());

        $post->setPublished(true);
        $this->assertTrue($post->isPublished());
    }

    public function testSetTitleThrowsExceptionWhenTooShort(): void
    {
        $post = new PostModel();
        $this->expectException(\InvalidArgumentException::class);
        $post->setTitle('Short');
    }

    public function testTagsManagement(): void
    {
        $post = new PostModel();
        $tag = new TagModel();
        $tag->setName('PHP');

        $this->assertCount(0, $post->getTags());

        $post->addTag($tag);
        $this->assertCount(1, $post->getTags());
        $this->assertTrue($post->getTags()->contains($tag));

        // Add same tag again
        $post->addTag($tag);
        $this->assertCount(1, $post->getTags());

        $post->removeTag($tag);
        $this->assertCount(0, $post->getTags());
    }

    public function testAddTags(): void
    {
        $post = new PostModel();
        $tag1 = new TagModel();
        $tag1->setName('Tag 1');
        $tag2 = new TagModel();
        $tag2->setName('Tag 2');

        $tags = new \Doctrine\Common\Collections\ArrayCollection([$tag1, $tag2]);
        $post->addTags($tags);

        $this->assertCount(2, $post->getTags());
        $this->assertTrue($post->getTags()->contains($tag1));
        $this->assertTrue($post->getTags()->contains($tag2));
    }
}
