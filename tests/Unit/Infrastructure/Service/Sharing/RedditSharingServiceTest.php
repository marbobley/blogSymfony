<?php

declare(strict_types=1);

namespace App\Tests\Unit\Infrastructure\Service\Sharing;

use App\Infrastructure\Service\Sharing\RedditSharingService;
use PHPUnit\Framework\TestCase;

class RedditSharingServiceTest extends TestCase
{
    private RedditSharingService $service;

    protected function setUp(): void
    {
        $this->service = new RedditSharingService();
    }

    public function testGetPlatformName(): void
    {
        $this->assertEquals('Reddit', $this->service->getPlatformName());
    }

    public function testGetShareUrl(): void
    {
        $url = 'https://example.com/post/my-article';
        $title = 'Mon super article';

        $shareUrl = $this->service->getShareUrl($url, $title);

        $this->assertStringContainsString('https://reddit.com/submit', $shareUrl);
        $this->assertStringContainsString('url=' . urlencode($url), $shareUrl);
        $this->assertStringContainsString('title=' . urlencode($title), $shareUrl);
    }
}
