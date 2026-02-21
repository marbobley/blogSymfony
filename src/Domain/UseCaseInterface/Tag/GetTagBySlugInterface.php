<?php

declare(strict_types=1);

namespace App\Domain\UseCaseInterface\Tag;

use App\Domain\Model\TagModel;

interface GetTagBySlugInterface
{
    public function execute(string $slug): TagModel;
}
