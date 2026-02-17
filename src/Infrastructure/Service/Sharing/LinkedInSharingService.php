<?php

declare(strict_types=1);

namespace App\Infrastructure\Service\Sharing;

use App\Domain\Service\Sharing\SharingServiceInterface;

use function urlencode;

class LinkedInSharingService implements SharingServiceInterface
{
    private const BASE_URL = 'https://www.linkedin.com/sharing/share-offsite/';

    public function getShareUrl(string $url, string $title): string
    {
        return self::BASE_URL . '?url=' . urlencode($url);
    }

    public function getPlatformName(): string
    {
        return 'LinkedIn';
    }
}
