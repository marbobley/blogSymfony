<?php

declare(strict_types=1);

namespace App\Domain\UseCaseInterface\Tag;

use App\Domain\Model\TagModel;

interface CreateTagInterface
{
    public function execute(TagModel $tagDTO): TagModel;
}
