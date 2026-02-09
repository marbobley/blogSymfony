<?php

declare(strict_types=1);

namespace App\Domain\UseCase;

use App\Domain\Provider\SeoProviderInterface;
use App\Domain\UseCaseInterface\DeleteSeoInterface;

final readonly class DeleteSeo implements DeleteSeoInterface
{
    public function __construct(
        private SeoProviderInterface $seoProvider
    ) {
    }

    public function execute(string $identifier): void
    {
        $this->seoProvider->delete($identifier);
    }
}
