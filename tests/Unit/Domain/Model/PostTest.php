<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Model;

use App\Infrastructure\Entity\Post;
use PHPUnit\Framework\TestCase;

class PostTest extends TestCase
{
    public function testAddAndRemoveTag(): void
    {
        $post = new Post('Titre du post', 'Contenu du post');
        $tag = new \App\Infrastructure\Entity\Tag('Symfony');

        $post->addTag($tag);

        $this->assertCount(1, $post->getTags());
        $this->assertTrue($post->getTags()->contains($tag));

        $post->removeTag($tag);

        $this->assertCount(0, $post->getTags());
        $this->assertFalse($post->getTags()->contains($tag));
    }

    public function testPublishedStatus(): void
    {
        $post = new Post('Titre du post', 'Contenu du post');

        $this->assertFalse($post->isPublished());

        $post->setPublished(true);
        $this->assertTrue($post->isPublished());

        $post->setPublished(false);
        $this->assertFalse($post->isPublished());
    }
}
