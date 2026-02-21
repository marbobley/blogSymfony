<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Factory;

use App\Domain\Factory\PostModelBuilder;
use App\Domain\Model\StatutArticle;
use App\Tests\Helper\XmlTestDataTrait;
use Exception;
use PHPUnit\Framework\TestCase;

class PostModelFactoryTest extends TestCase
{
    use XmlTestDataTrait;

    /**
     * @throws Exception
     */
    public function testCreate(): void
    {
        $postXml = $this->loadPostModelsFromXml(__DIR__ . '/../../../Fixtures/posts.xml')[0];
        $postBuilder = new PostModelBuilder();

        $statut = $postXml->isPublished() ? StatutArticle::PUBLISHED : StatutArticle::DRAFT ;

        $postModel = $postBuilder
            ->setId($postXml->getId())
            ->setTitle($postXml->getTitle())
            ->setContent($postXml->getContent())
            ->setSlug($postXml->getSlug())
            ->setSubTitle($postXml->getSubTitle())
            ->setCreatedAt($postXml->getCreatedAt())
            ->setUpdatedAt($postXml->getUpdatedAt())
            ->setTags($postXml->getTags())
            ->setPublished($statut)
            ->build();

        $this->assertSame($postXml->getId(), $postModel->getId());
        $this->assertSame($postXml->getTitle(), $postModel->getTitle());
        $this->assertSame($postXml->getContent(), $postModel->getContent());
        $this->assertSame($postXml->getSlug(), $postModel->getSlug());
        $this->assertSame($postXml->getSubTitle(), $postModel->getSubTitle());
        $this->assertSame($postXml->getCreatedAt(), $postModel->getCreatedAt());
        $this->assertSame($postXml->getUpdatedAt(), $postModel->getUpdatedAt());
        $this->assertSame($postXml->isPublished(), $postModel->isPublished());
        $this->assertSame($postXml->getTags()[0], $postModel->getTags()[0]);
    }
}
