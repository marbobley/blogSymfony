<?php

declare(strict_types=1);

namespace App\Application\UseCaseInterface;

use App\Application\Model\TagModel;
use App\Domain\Model\Tag;

interface CreateTagInterface
{
    public function execute(TagModel $tagDTO): Tag;
}
