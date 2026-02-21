<?php

declare(strict_types=1);

namespace App\Domain\UseCaseInterface\Tag;

interface DeleteTagInterface
{
    public function execute(int $id): void;
}
