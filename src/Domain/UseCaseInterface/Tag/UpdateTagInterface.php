<?php

declare(strict_types=1);

namespace App\Domain\UseCaseInterface\Tag;

use App\Domain\Model\TagModel;

interface UpdateTagInterface
{
    public function execute(int $id, TagModel $tagDTO): TagModel;
}
