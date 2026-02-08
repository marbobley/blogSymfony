<?php

declare(strict_types=1);

namespace App\Infrastructure\Adapter;

use App\Domain\Exception\EntityNotFoundException;
use App\Domain\Model\SeoModel;
use App\Domain\Provider\SeoProviderInterface;
use App\Infrastructure\MapperInterface\SeoDataMapperInterface;
use App\Infrastructure\Repository\SeoDataRepositoryInterface;

final readonly class SeoAdapter implements SeoProviderInterface
{
    public function __construct(
        private SeoDataRepositoryInterface $repository,
        private SeoDataMapperInterface $mapper,
    ) {
    }

    public function findByPageIdentifier(string $identifier): ?SeoModel
    {

        $entity = $this->repository->findByPageIdentifier($identifier);
        if (!$entity) {
            throw EntityNotFoundException::forEntity('SeoData', $identifier);
        }
        return $this->mapper->toModel($entity);
    }
}
