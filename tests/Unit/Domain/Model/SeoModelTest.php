<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Model;

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
                metaRobots: 'index, follow'
            ),
            social: new SocialSeo(
                ogTitle: 'OG Title',
                ogDescription: 'OG Description',
                ogImage: 'https://example.com/image.jpg',
                ogType: 'website',
                twitterCard: 'summary_large_image'
            ),
            sitemap: new SitemapSeo(
                inSitemap: true,
                changefreq: 'daily',
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
        $this->assertEquals('index, follow', $seo->getCore()->getMetaRobots());
        $this->assertEquals('OG Title', $seo->getSocial()->getOgTitle());
        $this->assertEquals('OG Description', $seo->getSocial()->getOgDescription());
        $this->assertEquals('https://example.com/image.jpg', $seo->getSocial()->getOgImage());
        $this->assertEquals('website', $seo->getSocial()->getOgType());
        $this->assertEquals('summary_large_image', $seo->getSocial()->getTwitterCard());
        $this->assertTrue($seo->getSitemap()->isInSitemap());
        $this->assertEquals('daily', $seo->getSitemap()->getChangefreq());
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
        $this->assertEquals('index, follow', $seo->getCore()->getMetaRobots());
        $this->assertTrue($seo->getSitemap()->isInSitemap());
        $this->assertEquals('weekly', $seo->getSitemap()->getChangefreq());
        $this->assertEquals(0.5, $seo->getSitemap()->getPriority());
        $this->assertFalse($seo->getMeta()->isNoIndex());
    }
}
