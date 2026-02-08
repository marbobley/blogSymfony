<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Model;

use App\Infrastructure\Entity\Post;
use App\Infrastructure\Entity\Tag;
use PHPUnit\Framework\TestCase;

class TagTest extends TestCase
{
    public function testTagCreation(): void
    {
        $tag = new Tag('Symfony');

        $this->assertEquals('Symfony', $tag->getName());
        $this->assertCount(0, $tag->getPosts());
    }

    public function testSetName(): void
    {
        $tag = new Tag('Symfony');
        $tag->setName('PHP');

        $this->assertEquals('PHP', $tag->getName());
    }

    public function testAddAndRemovePost(): void
    {
        $tag = new Tag('Symfony');
        $post = new Post('Titre du post', 'Contenu du post');

        $tag->addPost($post);

        $this->assertCount(1, $tag->getPosts());
        $this->assertTrue($tag->getPosts()->contains($post));
        $this->assertTrue($post->getTags()->contains($tag));

        $tag->removePost($post);

        $this->assertCount(0, $tag->getPosts());
        $this->assertFalse($tag->getPosts()->contains($post));
        $this->assertFalse($post->getTags()->contains($tag));
    }
}
