<?php

declare(strict_types=1);

namespace App\Application\UseCaseInterface;

use App\Application\DTO\PostResponseDTO;

interface GetPostInterface
{
    public function execute(int $id): PostResponseDTO;
}
