<?php

declare(strict_types=1);

namespace App\Domain\UseCaseInterface;

use App\Domain\Model\TagModel;

interface GetTagBySlugInterface
{
    public function execute(string $slug): TagModel;
}
