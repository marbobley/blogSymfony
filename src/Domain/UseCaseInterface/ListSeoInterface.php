<?php

declare(strict_types=1);

namespace App\Domain\UseCaseInterface;

use App\Domain\Model\SeoModel;

interface ListSeoInterface
{
    /** @return SeoModel[] */
    public function execute(): array;
}
