<?php

declare(strict_types=1);

namespace App\Application\UseCaseInterface;

use App\Application\DTO\PostResponseDTO;

interface GetPostBySlugInterface
{
    public function execute(string $slug): PostResponseDTO;
}
