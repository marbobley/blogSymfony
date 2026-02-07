<?php

declare(strict_types=1);

namespace App\Application\UseCaseInterface;

use App\Application\Model\PostModel;

interface ListPostsInterface
{
    /**
     * @return PostModel[]
     */
    public function execute(?int $tagId = null): array;
}
