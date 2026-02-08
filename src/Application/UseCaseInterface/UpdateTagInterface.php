<?php

declare(strict_types=1);

namespace App\Application\UseCaseInterface;

use App\Application\Model\TagModel;

interface UpdateTagInterface
{
    public function execute(int $id, TagModel $tagDTO): TagModel;
}
