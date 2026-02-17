<?php

declare(strict_types=1);

namespace App\Infrastructure\Service\Social;

use App\Domain\Service\Social\SocialLinkInterface;

class WebsiteSocialLink implements SocialLinkInterface
{
    public function getUrl(): string
    {
        return 'https://wfdevelopment.fr/home';
    }

    public function getPlatformName(): string
    {
        return 'Site Web';
    }

    public function getIconClass(): string
    {
        return 'bi-globe';
    }
}
