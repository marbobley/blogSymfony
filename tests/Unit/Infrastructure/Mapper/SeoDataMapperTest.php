<?php

declare(strict_types=1);

namespace App\Tests\Unit\Infrastructure\Mapper;

use App\Domain\Enum\ChangeFreq;
use App\Domain\Enum\OgType;
use App\Domain\Enum\RobotsMode;
use App\Domain\Enum\TwitterCard;
use App\Domain\Model\Component\CoreSeo;
use App\Domain\Model\Component\MetaSeo;
use App\Domain\Model\Component\SitemapSeo;
use App\Domain\Model\Component\SocialSeo;
use App\Domain\Model\SeoModel;
use App\Infrastructure\Entity\SeoData;
use App\Infrastructure\Mapper\SeoDataMapper;
use PHPUnit\Framework\TestCase;

class SeoDataMapperTest extends TestCase
{
    private SeoDataMapper $mapper;

    protected function setUp(): void
    {
        $this->mapper = new SeoDataMapper();
    }

    public function testToModel(): void
    {
        $entity = new SeoData();
        $entity->setPageIdentifier('test-page');

        $entity->getCore()
            ->setTitle('Test Title')
            ->setMetaDescription('Test Description')
            ->setCanonicalUrl('https://test.com')
            ->setMetaRobots('noindex, nofollow');

        $entity->getSocial()
            ->setOgTitle('Social Title')
            ->setOgDescription('Social Description')
            ->setOgImage('https://test.com/img.png')
            ->setOgType('article')
            ->setTwitterCard('summary');

        $entity->getSitemap()
            ->setInSitemap(false)
            ->setChangefreq('always')
            ->setPriority('0.9');

        $entity->getMeta()
            ->setIsNoIndex(true)
            ->setSchemaMarkup(['@type' => 'Article'])
            ->setBreadcrumbTitle('Test Breadcrumb');

        $model = $this->mapper->toModel($entity);

        $this->assertEquals('test-page', $model->getPageIdentifier());
        $this->assertEquals('Test Title', $model->getCore()->getTitle());
        $this->assertEquals(RobotsMode::NOINDEX_NOFOLLOW, $model->getCore()->getMetaRobots());

        $this->assertEquals('Social Title', $model->getSocial()->getOgTitle());
        $this->assertEquals(OgType::ARTICLE, $model->getSocial()->getOgType());
        $this->assertEquals(TwitterCard::SUMMARY, $model->getSocial()->getTwitterCard());

        $this->assertFalse($model->getSitemap()->isInSitemap());
        $this->assertEquals(ChangeFreq::ALWAYS, $model->getSitemap()->getChangefreq());
        $this->assertEquals(0.9, $model->getSitemap()->getPriority());

        $this->assertTrue($model->getMeta()->isNoIndex());
        $this->assertEquals(['@type' => 'Article'], $model->getMeta()->getSchemaMarkup());
        $this->assertEquals('Test Breadcrumb', $model->getMeta()->getBreadcrumbTitle());
    }

    public function testToModelWithInvalidEnumsUsesDefaults(): void
    {
        $entity = new SeoData();
        $entity->setPageIdentifier('invalid-enums');
        $entity->getCore()->setMetaRobots('INVALID');
        $entity->getSocial()->setOgType('INVALID');
        $entity->getSocial()->setTwitterCard('INVALID');
        $entity->getSitemap()->setChangefreq('INVALID');

        $model = $this->mapper->toModel($entity);

        $this->assertEquals(RobotsMode::INDEX_FOLLOW, $model->getCore()->getMetaRobots());
        $this->assertEquals(OgType::WEBSITE, $model->getSocial()->getOgType());
        $this->assertEquals(TwitterCard::SUMMARY_LARGE_IMAGE, $model->getSocial()->getTwitterCard());
        $this->assertEquals(ChangeFreq::WEEKLY, $model->getSitemap()->getChangefreq());
    }

    public function testToEntity(): void
    {
        $model = new SeoModel(
            pageIdentifier: 'model-to-entity',
            core: new CoreSeo(
                title: 'Model Title',
                metaRobots: RobotsMode::INDEX_NOFOLLOW
            ),
            social: new SocialSeo(
                ogType: OgType::BOOK,
                twitterCard: TwitterCard::SUMMARY
            ),
            sitemap: new SitemapSeo(
                inSitemap: true,
                changefreq: ChangeFreq::DAILY,
                priority: 0.7
            ),
            meta: new MetaSeo(
                isNoIndex: false,
                breadcrumbTitle: 'Model Breadcrumb'
            )
        );

        $entity = $this->mapper->toEntity($model);

        $this->assertEquals('model-to-entity', $entity->getPageIdentifier());
        $this->assertEquals('Model Title', $entity->getCore()->getTitle());
        $this->assertEquals('index, nofollow', $entity->getCore()->getMetaRobots());
        $this->assertEquals('book', $entity->getSocial()->getOgType());
        $this->assertEquals('summary', $entity->getSocial()->getTwitterCard());
        $this->assertTrue($entity->getSitemap()->isInSitemap());
        $this->assertEquals('daily', $entity->getSitemap()->getChangefreq());
        $this->assertEquals('0.7', $entity->getSitemap()->getPriority());
        $this->assertEquals('Model Breadcrumb', $entity->getMeta()->getBreadcrumbTitle());
    }

    public function testUpdateEntity(): void
    {
        $entity = new SeoData();
        $entity->setPageIdentifier('old-id');

        $model = new SeoModel(
            pageIdentifier: 'new-id',
            core: new CoreSeo(title: 'New Title'),
            social: new SocialSeo(),
            sitemap: new SitemapSeo(),
            meta: new MetaSeo()
        );

        $this->mapper->updateEntity($entity, $model);

        $this->assertEquals('new-id', $entity->getPageIdentifier());
        $this->assertEquals('New Title', $entity->getCore()->getTitle());
    }
}
