<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Factory;

use App\Domain\Builder\TagModelBuilder;
use App\Tests\Helper\XmlTagDataTrait;
use PHPUnit\Framework\TestCase;

class TagModelFactoryTest extends TestCase
{
    use XmlTagDataTrait;
    public function testCreate(): void
    {
        $tagXml = $this->loadTagModelsFromXml()[0];
        $tagBuilder = new TagModelBuilder();
        $tagModel = $tagBuilder
            ->setId($tagXml->getId())
            ->setSlug($tagXml->getSlug())
            ->setName($tagXml->getName())
            ->build();

        $this->assertSame($tagXml->getId(), $tagModel->getId());
        $this->assertSame($tagXml->getName(), $tagModel->getName());
        $this->assertSame($tagXml->getSlug(), $tagModel->getSlug());
    }
}
