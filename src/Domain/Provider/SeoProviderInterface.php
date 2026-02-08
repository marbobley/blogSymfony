<?php

declare(strict_types=1);

namespace App\Domain\Provider;

use App\Domain\Model\SeoModel;

interface SeoProviderInterface
{
    public function findByPageIdentifier(string $identifier): ?SeoModel;
}
