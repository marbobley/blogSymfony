<?php

declare(strict_types=1);

namespace App\Domain\UseCase;

use App\Domain\Model\SeoModel;
use App\Domain\Provider\SeoProviderInterface;
use App\Domain\UseCaseInterface\SaveSeoInterface;

final readonly class SaveSeo implements SaveSeoInterface
{
    public function __construct(
        private SeoProviderInterface $seoProvider
    ) {
    }

    public function execute(SeoModel $seoModel): void
    {
        $this->seoProvider->save($seoModel);
    }
}
