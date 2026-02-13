<?php

declare(strict_types=1);

namespace App\Domain\UseCaseInterface;

use App\Domain\Model\PostModel;

interface ListPostsInterface
{
    /**
     * @return PostModel[]
     */
    public function execute(?int $tagId = null, bool $onlyPublished = true): array;
}
