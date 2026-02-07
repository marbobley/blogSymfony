<?php

declare(strict_types=1);

namespace App\Application\UseCaseInterface;

interface ListPostsInterface
{
    /**
     * @return \App\Application\DTO\PostResponseDTO[]
     */
    public function execute(?int $tagId = null): array;
}
