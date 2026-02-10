<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Model;

use App\Domain\Enum\ChangeFreq;
use App\Domain\Enum\RobotsMode;
use App\Domain\Enum\OgType;
use App\Domain\Enum\TwitterCard;
use App\Domain\Model\Component\CoreSeo;
use App\Domain\Model\Component\MetaSeo;
use App\Domain\Model\Component\SitemapSeo;
use App\Domain\Model\Component\SocialSeo;
use App\Domain\Model\SeoModel;
use PHPUnit\Framework\TestCase;

class SeoModelTest extends TestCase
{
    public function testConstructAndGetters(): void
    {
        $seo = new SeoModel(
            pageIdentifier: 'home',
            core: new CoreSeo(
                title: 'Home Page',
                metaDescription: 'Description of home page',
                canonicalUrl: 'https://example.com',
                favicon: 'https://example.com/favicon.ico',
                metaRobots: RobotsMode::INDEX_FOLLOW
            ),
            social: new SocialSeo(
                ogTitle: 'OG Title',
                ogDescription: 'OG Description',
                ogImage: 'https://example.com/image.jpg',
                ogType: OgType::WEBSITE,
                twitterCard: TwitterCard::SUMMARY_LARGE_IMAGE
            ),
            sitemap: new SitemapSeo(
                inSitemap: true,
                changefreq: ChangeFreq::DAILY,
                priority: 0.8
            ),
            meta: new MetaSeo(
                isNoIndex: false,
                schemaMarkup: ['@type' => 'WebPage'],
                breadcrumbTitle: 'Home'
            )
        );

        $this->assertEquals('home', $seo->getPageIdentifier());
        $this->assertEquals('Home Page', $seo->getCore()->getTitle());
        $this->assertEquals('Description of home page', $seo->getCore()->getMetaDescription());
        $this->assertEquals('https://example.com', $seo->getCore()->getCanonicalUrl());
        $this->assertEquals(RobotsMode::INDEX_FOLLOW, $seo->getCore()->getMetaRobots());
        $this->assertEquals('OG Title', $seo->getSocial()->getOgTitle());
        $this->assertEquals('OG Description', $seo->getSocial()->getOgDescription());
        $this->assertEquals('https://example.com/image.jpg', $seo->getSocial()->getOgImage());
        $this->assertEquals(OgType::WEBSITE, $seo->getSocial()->getOgType());
        $this->assertEquals(TwitterCard::SUMMARY_LARGE_IMAGE, $seo->getSocial()->getTwitterCard());
        $this->assertTrue($seo->getSitemap()->isInSitemap());
        $this->assertEquals(ChangeFreq::DAILY, $seo->getSitemap()->getChangefreq());
        $this->assertEquals(0.8, $seo->getSitemap()->getPriority());
        $this->assertFalse($seo->getMeta()->isNoIndex());
        $this->assertEquals(['@type' => 'WebPage'], $seo->getMeta()->getSchemaMarkup());
        $this->assertEquals('Home', $seo->getMeta()->getBreadcrumbTitle());
    }

    public function testDefaultValues(): void
    {
        $seo = new SeoModel(
            pageIdentifier: 'minimal',
            core: new CoreSeo(),
            social: new SocialSeo(),
            sitemap: new SitemapSeo(),
            meta: new MetaSeo()
        );

        $this->assertEquals('minimal', $seo->getPageIdentifier());
        $this->assertNull($seo->getCore()->getTitle());
        $this->assertEquals(RobotsMode::INDEX_FOLLOW, $seo->getCore()->getMetaRobots());
        $this->assertTrue($seo->getSitemap()->isInSitemap());
        $this->assertEquals(ChangeFreq::WEEKLY, $seo->getSitemap()->getChangefreq());
        $this->assertEquals(0.5, $seo->getSitemap()->getPriority());
        $this->assertFalse($seo->getMeta()->isNoIndex());
    }

    public function testInvalidPriorityThrowsException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new SitemapSeo(priority: 1.1);
    }

    public function testPriorityTooLowThrowsException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new SitemapSeo(priority: -0.1);
    }

    public function testValidPriorityBoundaries(): void
    {
        $min = new SitemapSeo(priority: 0.0);
        $max = new SitemapSeo(priority: 1.0);

        $this->assertEquals(0.0, $min->getPriority());
        $this->assertEquals(1.0, $max->getPriority());
    }
}
