<?php

namespace App\Application\UseCaseInterface;

interface ListPostsInterface
{
    /**
     * @return \App\Application\DTO\PostResponseDTO[]
     */
    public function execute(): array;
}
