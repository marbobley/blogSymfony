<?php

declare(strict_types=1);

namespace App\Infrastructure\Service\Sharing;

use App\Domain\Service\Sharing\SharingServiceInterface;

class RedditSharingService implements SharingServiceInterface
{
    private const BASE_URL = 'https://reddit.com/submit';

    public function getShareUrl(string $url, string $title): string
    {
        return self::BASE_URL . '?url=' . urlencode($url) . '&title=' . urlencode($title);
    }

    public function getPlatformName(): string
    {
        return 'Reddit';
    }
}
