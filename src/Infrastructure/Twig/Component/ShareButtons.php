<?php

declare(strict_types=1);

namespace App\Infrastructure\Twig\Component;

use App\Domain\Service\Sharing\SharingServiceInterface;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\Component\HttpFoundation\RequestStack;

#[AsTwigComponent('ShareButtons')]
final class ShareButtons
{
    /**
     * @param iterable<SharingServiceInterface> $sharingServices
     */
    public function __construct(
        private readonly iterable $sharingServices,
        private readonly RequestStack $requestStack
    ) {
    }

    public string $url = '';
    public string $title = '';

    /**
     * @return array<int, array{name: string, url: string}>
     */
    public function getLinks(): array
    {
        $links = [];
        $currentUrl = $this->url ?: $this->requestStack->getCurrentRequest()?->getUri() ?: '';

        foreach ($this->sharingServices as $service) {
            $links[] = [
                'name' => $service->getPlatformName(),
                'url' => $service->getShareUrl($currentUrl, $this->title),
            ];
        }

        return $links;
    }
}
