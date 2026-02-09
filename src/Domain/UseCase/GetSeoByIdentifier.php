<?php

declare(strict_types=1);

namespace App\Domain\UseCase;

use App\Domain\Model\SeoModel;
use App\Domain\Provider\SeoProviderInterface;
use App\Domain\UseCaseInterface\GetSeoByIdentifierInterface;

final readonly class GetSeoByIdentifier implements GetSeoByIdentifierInterface
{
    public function __construct(
        private SeoProviderInterface $seoProvider
    ) {
    }

    public function execute(string $identifier): ?SeoModel
    {
        return $this->seoProvider->findByPageIdentifier($identifier);
    }
}
