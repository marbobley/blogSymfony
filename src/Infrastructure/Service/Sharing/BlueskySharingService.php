<?php

declare(strict_types=1);

namespace App\Infrastructure\Service\Sharing;

use App\Domain\Service\Sharing\SharingServiceInterface;

class BlueskySharingService implements SharingServiceInterface
{
    private const BASE_URL = 'https://bsky.app/intent/compose';

    public function getShareUrl(string $url, string $title): string
    {
        return self::BASE_URL . '?text=' . urlencode($title . ' ' . $url);
    }

    public function getPlatformName(): string
    {
        return 'Bluesky';
    }
}
