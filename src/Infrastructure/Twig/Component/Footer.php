<?php

declare(strict_types=1);

namespace App\Infrastructure\Twig\Component;

use App\Domain\Service\Social\SocialLinkInterface;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('Footer')]
final class Footer
{
    /**
     * @param iterable<SocialLinkInterface> $socialLinks
     */
    public function __construct(
        private readonly iterable $socialLinks,
    ) {}

    /**
     * @return array<int, SocialLinkInterface>
     */
    public function getSocialLinks(): array
    {
        $links = [];
        foreach ($this->socialLinks as $link) {
            $links[] = $link;
        }

        return $links;
    }
}
