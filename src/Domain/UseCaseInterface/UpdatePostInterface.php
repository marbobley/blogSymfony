<?php

declare(strict_types=1);

namespace App\Domain\UseCaseInterface;

use App\Domain\Model\PostModel;

interface UpdatePostInterface
{
    public function execute(int $id, PostModel $postModel): PostModel;
}
