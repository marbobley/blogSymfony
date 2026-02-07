<?php

declare(strict_types=1);

namespace App\Application\UseCaseInterface;

use App\Application\DTO\TagDTO;
use App\Domain\Model\Tag;

interface UpdateTagInterface
{
    public function execute(int $id, TagDTO $tagDTO): Tag;
}
