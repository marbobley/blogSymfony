<?php

declare(strict_types=1);

namespace App\Domain\Service\Social;

interface SocialLinkInterface
{
    public function getUrl(): string;

    public function getPlatformName(): string;

    public function getIconClass(): string;
}
