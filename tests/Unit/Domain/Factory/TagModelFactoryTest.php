<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Factory;

use App\Domain\Factory\TagModelBuilder;
use App\Tests\Helper\XmlTestDataTrait;
use PHPUnit\Framework\TestCase;

class TagModelFactoryTest extends TestCase
{
    use XmlTestDataTrait;
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
