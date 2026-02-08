<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

interface SeoDataRepositoryInterface
{

    public function findByPageIdentifier(string $identifier);
}
