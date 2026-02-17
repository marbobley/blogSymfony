<?php

declare(strict_types=1);

namespace App\Infrastructure\Service\Social;

use App\Domain\Service\Social\SocialLinkInterface;

class GitHubSocialLink implements SocialLinkInterface
{
    public function getUrl(): string
    {
        return 'https://github.com/marbobley';
    }

    public function getPlatformName(): string
    {
        return 'GitHub';
    }

    public function getIconClass(): string
    {
        return 'bi-github';
    }
}
