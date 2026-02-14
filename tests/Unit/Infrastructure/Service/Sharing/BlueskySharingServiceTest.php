<?php

declare(strict_types=1);

namespace App\Tests\Unit\Infrastructure\Service\Sharing;

use App\Infrastructure\Service\Sharing\BlueskySharingService;
use PHPUnit\Framework\TestCase;

class BlueskySharingServiceTest extends TestCase
{
    private BlueskySharingService $service;

    protected function setUp(): void
    {
        $this->service = new BlueskySharingService();
    }

    public function testGetPlatformName(): void
    {
        $this->assertEquals('Bluesky', $this->service->getPlatformName());
    }

    public function testGetShareUrl(): void
    {
        $url = 'https://example.com/post';
        $title = 'Mon super article';
        $expected = 'https://bsky.app/intent/compose?text=' . urlencode($title . ' ' . $url);

        $this->assertEquals($expected, $this->service->getShareUrl($url, $title));
    }
}
