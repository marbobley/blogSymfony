<?php

declare(strict_types=1);

namespace App\Application\UseCaseInterface;

use App\Application\Model\TagModel;

interface CreateTagInterface
{
    public function execute(TagModel $tagDTO): TagModel;
}
