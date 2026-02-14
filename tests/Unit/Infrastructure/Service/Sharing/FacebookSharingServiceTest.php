<?php

declare(strict_types=1);

namespace App\Tests\Unit\Infrastructure\Service\Sharing;

use App\Infrastructure\Service\Sharing\FacebookSharingService;
use PHPUnit\Framework\TestCase;

class FacebookSharingServiceTest extends TestCase
{
    public function testGetShareUrl(): void
    {
        $service = new FacebookSharingService();
        $url = 'https://example.com/post/mon-article';
        $title = 'Mon Article';

        $shareUrl = $service->getShareUrl($url, $title);

        $this->assertStringContainsString('facebook.com/sharer/sharer.php', $shareUrl);
        $this->assertStringContainsString('u=' . urlencode($url), $shareUrl);
    }

    public function testGetPlatformName(): void
    {
        $service = new FacebookSharingService();
        $this->assertEquals('Facebook', $service->getPlatformName());
    }
}
