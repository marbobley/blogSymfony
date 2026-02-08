<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Factory;

use App\Domain\Factory\TagModelFactory;
use App\Domain\Model\TagModel;
use PHPUnit\Framework\TestCase;

class TagModelFactoryTest extends TestCase
{
    public function testCreate(): void
    {
        $id = 1;
        $name = 'Test Tag';
        $slug = 'test-tag';

        $tagModel = TagModelFactory::create($id, $name, $slug);

        $this->assertInstanceOf(TagModel::class, $tagModel);
        $this->assertSame($id, $tagModel->getId());
        $this->assertSame($name, $tagModel->getName());
        $this->assertSame($slug, $tagModel->getSlug());
    }
}
