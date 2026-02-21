<?php

declare(strict_types=1);

namespace App\Domain\UseCaseInterface\Post;

use App\Domain\Criteria\PostCriteria;
use App\Domain\Model\PostModel;

interface ListAllPostsInterface
{
    /**
     * @return PostModel[]
     */
    public function execute(?PostCriteria $criteria = null): array;
}
