<?php

declare(strict_types=1);

namespace App\Application\UseCaseInterface;

interface DeletePostInterface
{
    public function execute(int $id): void;
}
