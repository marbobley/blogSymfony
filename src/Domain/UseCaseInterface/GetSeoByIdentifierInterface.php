<?php

declare(strict_types=1);

namespace App\Domain\UseCaseInterface;

use App\Domain\Model\SeoModel;

interface GetSeoByIdentifierInterface
{
    public function execute(string $identifier): ?SeoModel;
}
