<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Model;

use App\Domain\Model\LikeModel;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class LikeModelTest extends TestCase
{
    public function testGettersSetters(): void
    {
        $like = new LikeModel();
        $createdAt = new DateTimeImmutable();

        $like->setId(1);
        $like->setPostId(10);
        $like->setUserId(100);
        $like->setCreatedAt($createdAt);

        $this->assertEquals(1, $like->getId());
        $this->assertEquals(10, $like->getPostId());
        $this->assertEquals(100, $like->getUserId());
        $this->assertEquals($createdAt, $like->getCreatedAt());
    }
}
