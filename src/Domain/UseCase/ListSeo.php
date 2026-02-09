<?php

declare(strict_types=1);

namespace App\Domain\UseCase;

use App\Domain\Provider\SeoProviderInterface;
use App\Domain\UseCaseInterface\ListSeoInterface;

final readonly class ListSeo implements ListSeoInterface
{
    public function __construct(
        private SeoProviderInterface $seoProvider
    ) {
    }

    public function execute(): array
    {
        return $this->seoProvider->findAll();
    }
}
