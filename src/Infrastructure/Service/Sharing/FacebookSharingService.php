<?php

declare(strict_types=1);

namespace App\Infrastructure\Service\Sharing;

use App\Domain\Service\Sharing\SharingServiceInterface;

use function urlencode;

class FacebookSharingService implements SharingServiceInterface
{
    private const BASE_URL = 'https://www.facebook.com/sharer/sharer.php';

    public function getShareUrl(string $url, string $title): string
    {
        return self::BASE_URL . '?u=' . urlencode($url);
    }

    public function getPlatformName(): string
    {
        return 'Facebook';
    }
}
