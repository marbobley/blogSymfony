<?php

declare(strict_types=1);

namespace App\Application\UseCaseInterface;

interface DeleteTagInterface
{
    public function execute(int $id): void;
}
