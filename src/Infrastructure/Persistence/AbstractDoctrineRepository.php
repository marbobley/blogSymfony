<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence;

use Doctrine\ORM\EntityManagerInterface;

/**
 * @template T of object
 */
abstract class AbstractDoctrineRepository
{
    /**
     * @param class-string<T> $entityClass
     */
    public function __construct(
        protected EntityManagerInterface $entityManager,
        protected string $entityClass,
    ) {}

    /**
     * @param T $entity
     */
    public function save(object $entity): void
    {
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }

    /**
     * @return T[]
     */
    public function findAll(): array
    {
        /** @var T[] $result */
        return $this->entityManager->getRepository($this->entityClass)->findAll();
    }

    /**
     * @return T|null
     */
    public function findById(int $id): ?object
    {
        /** @var T|null $result */
        return $this->entityManager->getRepository($this->entityClass)->find($id);
    }

    /**
     * @return T|null
     */
    public function findBySlug(string $slug): ?object
    {
        /** @var T|null $result */
        return $this->entityManager->getRepository($this->entityClass)->findOneBy(['slug' => $slug]);
    }

    /**
     * @return T|null
     */
    public function findByName(string $name): ?object
    {
        /** @var T|null $result */
        return $this->entityManager->getRepository($this->entityClass)->findOneBy(['name' => $name]);
    }

    /**
     * @param T $entity
     */
    public function delete(object $entity): void
    {
        $this->entityManager->remove($entity);
        $this->entityManager->flush();
    }
}
