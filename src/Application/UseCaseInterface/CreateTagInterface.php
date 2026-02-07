<?php

declare(strict_types=1);

namespace App\Application\UseCaseInterface;

use App\Application\DTO\TagDTO;
use App\Domain\Model\Tag;

interface CreateTagInterface
{
    public function execute(TagDTO $tagDTO): Tag;
}
