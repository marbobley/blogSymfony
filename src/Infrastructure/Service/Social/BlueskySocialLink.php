<?php

declare(strict_types=1);

namespace App\Infrastructure\Service\Social;

use App\Domain\Service\Social\SocialLinkInterface;

class BlueskySocialLink implements SocialLinkInterface
{
    public function getUrl(): string
    {
        return 'https://bsky.app/profile/marbobley.bsky.social';
    }

    public function getPlatformName(): string
    {
        return 'BlueSky';
    }

    public function getIconClass(): string
    {
        return 'bi-bluesky';
    }
}
