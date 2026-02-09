<?php

declare(strict_types=1);

namespace App\Domain\UseCaseInterface;

use App\Domain\Model\SeoModel;

interface SaveSeoInterface
{
    public function execute(SeoModel $seoModel): void;
}
