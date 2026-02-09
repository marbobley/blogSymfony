<?php

declare(strict_types=1);

namespace App\Domain\Provider;

use App\Domain\Model\SeoModel;

interface SeoProviderInterface
{
    public function findByPageIdentifier(string $identifier): ?SeoModel;

    /** @return SeoModel[] */
    public function findAll(): array;

    public function save(SeoModel $seoModel): void;

    public function delete(string $identifier): void;
}
