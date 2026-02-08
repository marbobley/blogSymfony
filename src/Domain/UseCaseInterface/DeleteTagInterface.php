<?php

declare(strict_types=1);

namespace App\Domain\UseCaseInterface;

interface DeleteTagInterface
{
    public function execute(int $id): void;
}
