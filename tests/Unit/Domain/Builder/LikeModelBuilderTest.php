<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Builder;

use App\Domain\Builder\LikeModelBuilder;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class LikeModelBuilderTest extends TestCase
{
    public function testBuild(): void
    {
        $builder = new LikeModelBuilder();
        $createdAt = new DateTimeImmutable();

        $like = $builder
            ->setId(1)
            ->setIdPost(10)
            ->setUserId(100)
            ->setCreatedAt($createdAt)
            ->build();

        $this->assertEquals(1, $like->getId());
        $this->assertEquals(10, $like->getPostId());
        $this->assertEquals(100, $like->getUserId());
        $this->assertEquals($createdAt, $like->getCreatedAt());
    }
}
