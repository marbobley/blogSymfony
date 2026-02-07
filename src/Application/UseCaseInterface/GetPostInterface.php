<?php

declare(strict_types=1);

namespace App\Application\UseCaseInterface;

use App\Application\Model\PostModel;

interface GetPostInterface
{
    public function execute(int $id): PostModel;
}
