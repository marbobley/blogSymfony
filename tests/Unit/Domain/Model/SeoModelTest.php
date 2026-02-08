<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Model;

use App\Domain\Model\SeoModel;
use PHPUnit\Framework\TestCase;

class SeoModelTest extends TestCase
{
    public function testConstructAndGetters(): void
    {
        $seo = new SeoModel(
            pageIdentifier: 'home',
            title: 'Home Page',
            metaDescription: 'Description of home page',
            canonicalUrl: 'https://example.com',
            metaRobots: 'index, follow',
            ogTitle: 'OG Title',
            ogDescription: 'OG Description',
            ogImage: 'https://example.com/image.jpg',
            ogType: 'website',
            twitterCard: 'summary_large_image',
            inSitemap: true,
            changefreq: 'daily',
            priority: 0.8,
            isNoIndex: false,
            schemaMarkup: ['@type' => 'WebPage'],
            breadcrumbTitle: 'Home'
        );

        $this->assertEquals('home', $seo->getPageIdentifier());
        $this->assertEquals('Home Page', $seo->getTitle());
        $this->assertEquals('Description of home page', $seo->getMetaDescription());
        $this->assertEquals('https://example.com', $seo->getCanonicalUrl());
        $this->assertEquals('index, follow', $seo->getMetaRobots());
        $this->assertEquals('OG Title', $seo->getOgTitle());
        $this->assertEquals('OG Description', $seo->getOgDescription());
        $this->assertEquals('https://example.com/image.jpg', $seo->getOgImage());
        $this->assertEquals('website', $seo->getOgType());
        $this->assertEquals('summary_large_image', $seo->getTwitterCard());
        $this->assertTrue($seo->isInSitemap());
        $this->assertEquals('daily', $seo->getChangefreq());
        $this->assertEquals(0.8, $seo->getPriority());
        $this->assertFalse($seo->isNoIndex());
        $this->assertEquals(['@type' => 'WebPage'], $seo->getSchemaMarkup());
        $this->assertEquals('Home', $seo->getBreadcrumbTitle());
    }

    public function testDefaultValues(): void
    {
        $seo = new SeoModel(pageIdentifier: 'minimal');

        $this->assertEquals('minimal', $seo->getPageIdentifier());
        $this->assertNull($seo->getTitle());
        $this->assertEquals('index, follow', $seo->getMetaRobots());
        $this->assertTrue($seo->isInSitemap());
        $this->assertEquals('weekly', $seo->getChangefreq());
        $this->assertEquals(0.5, $seo->getPriority());
        $this->assertFalse($seo->isNoIndex());
    }

    public function testFallbackGetters(): void
    {
        $seo = new SeoModel(
            pageIdentifier: 'fallback',
            title: 'Main Title',
            metaDescription: 'Main Description'
        );

        $this->assertEquals('Main Title', $seo->getOgTitle());
        $this->assertEquals('Main Description', $seo->getOgDescription());
        $this->assertEquals('Main Title', $seo->getBreadcrumbTitle());
    }

    public function testNoIndexRobots(): void
    {
        $seo = new SeoModel(
            pageIdentifier: 'noindex',
            isNoIndex: true
        );

        $this->assertEquals('noindex, nofollow', $seo->getMetaRobots());
    }
}
