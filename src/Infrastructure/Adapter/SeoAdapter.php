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
            return null;
        }
        return $this->mapper->toModel($entity);
    }

    public function findAll(): array
    {
        $entities = $this->repository->findAll();
        return array_map(fn($entity) => $this->mapper->toModel($entity), $entities);
    }

    public function save(SeoModel $seoModel): void
    {
        $entity = $this->repository->findByPageIdentifier($seoModel->getPageIdentifier());

        if ($entity) {
            $this->mapper->updateEntity($entity, $seoModel);
        } else {
            $entity = $this->mapper->toEntity($seoModel);
        }

        $this->repository->save($entity);
    }

    public function delete(string $identifier): void
    {
        $entity = $this->repository->findByPageIdentifier($identifier);
        if ($entity) {
            $this->repository->delete($entity);
        }
    }
}
