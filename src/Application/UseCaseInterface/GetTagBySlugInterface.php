<?php

declare(strict_types=1);

namespace App\Application\UseCaseInterface;

use App\Application\Model\TagResponseModel;

interface GetTagBySlugInterface
{
    public function execute(string $slug): TagResponseModel;
}
