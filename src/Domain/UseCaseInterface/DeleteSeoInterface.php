<?php

declare(strict_types=1);

namespace App\Domain\UseCaseInterface;

interface DeleteSeoInterface
{
    public function execute(string $identifier): void;
}
