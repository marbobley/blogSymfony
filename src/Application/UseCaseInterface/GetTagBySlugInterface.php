<?php

declare(strict_types=1);

namespace App\Application\UseCaseInterface;

use App\Application\Model\TagModel;

interface GetTagBySlugInterface
{
    public function execute(string $slug): TagModel;
}
