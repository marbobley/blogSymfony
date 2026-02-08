<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Factory;

use App\Domain\Factory\PostModelFactory;
use App\Domain\Model\PostModel;
use PHPUnit\Framework\TestCase;

class PostModelFactoryTest extends TestCase
{
    public function testCreate(): void
    {
        $title = 'Test Title';
        $content = 'Test Content';

        $postModel = PostModelFactory::create($title, $content);

        $this->assertInstanceOf(PostModel::class, $postModel);
        $this->assertSame($title, $postModel->getTitle());
        $this->assertSame($content, $postModel->getContent());
    }

    public function testCreateWithDefaultValues(): void
    {
        $postModel = PostModelFactory::create();

        $this->assertInstanceOf(PostModel::class, $postModel);
        $this->assertSame('', $postModel->getTitle());
        $this->assertSame('', $postModel->getContent());
    }
}
