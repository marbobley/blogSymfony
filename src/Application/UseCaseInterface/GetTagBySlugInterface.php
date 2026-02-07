<?php

declare(strict_types=1);

namespace App\Application\UseCaseInterface;

use App\Application\DTO\TagResponseDTO;

interface GetTagBySlugInterface
{
    public function execute(string $slug): TagResponseDTO;
}
