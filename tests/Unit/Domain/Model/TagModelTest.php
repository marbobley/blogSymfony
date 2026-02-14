<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Model;

use App\Domain\Model\TagModel;
use PHPUnit\Framework\TestCase;

class TagModelTest extends TestCase
{
    public function testTagModelAccessors(): void
    {
        $tag = new TagModel();

        $tag->setId(1);
        $this->assertEquals(1, $tag->getId());

        $tag->setName('Symfony');
        $this->assertEquals('Symfony', $tag->getName());

        $tag->setSlug('symfony');
        $this->assertEquals('symfony', $tag->getSlug());
    }

    public function testSetNameThrowsExceptionWhenTooShort(): void
    {
        $tag = new TagModel();
        $this->expectException(\InvalidArgumentException::class);
        $tag->setName('S');
    }
}
