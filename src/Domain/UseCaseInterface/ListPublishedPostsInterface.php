<?php

declare(strict_types=1);

namespace App\Domain\UseCaseInterface;

use App\Domain\Criteria\PostCriteria;
use App\Domain\Model\PostModel;

interface ListPublishedPostsInterface
{
    /**
     * @return PostModel[]
     */
    public function execute(?PostCriteria $criteria = null): array;
}
