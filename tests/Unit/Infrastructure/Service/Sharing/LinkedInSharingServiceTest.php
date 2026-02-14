<?php

declare(strict_types=1);

namespace App\Tests\Unit\Infrastructure\Service\Sharing;

use App\Infrastructure\Service\Sharing\LinkedInSharingService;
use PHPUnit\Framework\TestCase;

class LinkedInSharingServiceTest extends TestCase
{
    private LinkedInSharingService $service;

    protected function setUp(): void
    {
        $this->service = new LinkedInSharingService();
    }

    public function testGetPlatformName(): void
    {
        $this->assertEquals('LinkedIn', $this->service->getPlatformName());
    }

    public function testGetShareUrl(): void
    {
        $url = 'https://monblog.com/post/mon-article';
        $title = 'Mon super article';
        $expected = 'https://www.linkedin.com/sharing/share-offsite/?url=' . urlencode($url);

        $this->assertEquals($expected, $this->service->getShareUrl($url, $title));
    }
}
