<?php

declare(strict_types=1);

namespace App\Domain\Service\Sharing;

interface SharingServiceInterface
{
    /**
     * Retourne l'URL de partage pour une plateforme spécifique.
     */
    public function getShareUrl(string $url, string $title): string;

    /**
     * Retourne le nom de la plateforme (ex: Facebook).
     */
    public function getPlatformName(): string;
}
