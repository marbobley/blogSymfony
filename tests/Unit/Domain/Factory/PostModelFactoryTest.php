<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Factory;

use App\Domain\Factory\PostModelBuilder;
use App\Domain\Model\StatutArticle;
use App\Tests\Helper\AssertPostTrait;
use App\Tests\Helper\XmlPostDataTrait;
use Exception;
use PHPUnit\Framework\TestCase;

class PostModelFactoryTest extends TestCase
{
    use XmlPostDataTrait;
    use AssertPostTrait;

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

        $this->assertPostModelExpectedEqualsPostModelResult($this, $postXml, $postModel);
    }
}
