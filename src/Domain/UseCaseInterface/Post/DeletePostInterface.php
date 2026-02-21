<?php

declare(strict_types=1);

namespace App\Domain\UseCaseInterface\Post;

interface DeletePostInterface
{
    public function execute(int $id): void;
}
