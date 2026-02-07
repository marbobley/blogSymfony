<?php

declare(strict_types=1);

namespace App\Application\UseCaseInterface;


use App\Application\Model\TagModel;

interface GetTagInterface
{
    public function execute(int $id): TagModel;
}
